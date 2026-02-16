import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { UsersModule } from './users/users.module';
import { NotesModule } from './notes/notes.module';

@Module({
  imports: [
    TypeOrmModule.forRoot({
      type: 'mysql',
      host: 'localhost',
      port: 3306,
      username: 'root',
      password: 'YOUR_MYSQL_PASSWORD',
      database: 'diary',
      autoLoadEntities: true,
      synchronize: true,
    }),
    UsersModule,
    NotesModule,
  ],
})
export class AppModule {}
