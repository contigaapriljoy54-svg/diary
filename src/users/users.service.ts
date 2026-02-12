import { Injectable, BadRequestException, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Users } from './users.entity';
import { CreateUserDto, LoginUserDto, UpdateUserDto } from './users.dto';
import * as bcrypt from 'bcrypt';

@Injectable()
export class UsersService {
  constructor(
    @InjectRepository(Users)
    private usersRepo: Repository<Users>,
  ) {}

  async checkdb(): Promise<string> {
    try {
      await this.usersRepo.query('SELECT 1');
      return 'Database connected successfully';
    } catch (error) {
      return `Database connection failed: ${error.message}`;
    }
  }

  async register(dto: CreateUserDto): Promise<Users> {
    const existing = await this.usersRepo.findOne({ where: { email: dto.email } });
    if (existing) throw new BadRequestException('Email already exists');
    const hashedPassword = await bcrypt.hash(dto.password, 10);
    const user = this.usersRepo.create({ ...dto, password: hashedPassword });
    return this.usersRepo.save(user);
  }

  async login(dto: LoginUserDto): Promise<Users> {
    const user = await this.usersRepo.findOne({ where: { email: dto.email } });
    if (!user) throw new NotFoundException('User not found');
    const valid = await bcrypt.compare(dto.password, user.password);
    if (!valid) throw new BadRequestException('Invalid credentials');
    return user;
  }

  async findAll(): Promise<Users[]> {
    return this.usersRepo.find();
  }

  async findOne(id: number): Promise<Users> {
    const user = await this.usersRepo.findOne({ where: { id } });
    if (!user) throw new NotFoundException(`User with ID ${id} not found`);
    return user;
  }

  async update(id: number, dto: UpdateUserDto): Promise<Users> {
    const user = await this.findOne(id);
    if (dto.password) dto.password = await bcrypt.hash(dto.password, 10);
    Object.assign(user, dto);
    return this.usersRepo.save(user);
  }

  async delete(id: number): Promise<{ message: string }> {
    const user = await this.findOne(id);
    await this.usersRepo.remove(user);
    return { message: `User with ID ${id} deleted successfully` };
  }
}
