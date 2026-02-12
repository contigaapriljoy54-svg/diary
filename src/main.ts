import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';

async function bootstrap() {
  const app = await NestFactory.create(AppModule);
  
  app.enableCors({
    origin: [
      'http://127.0.0.1:5500', 
      'http://localhost:5500', 
      'null',
    ],
    methods: 'GET,HEAD,PUT,PATCH,PUSH,DELETE',
    credentials: true,
  });
  
  await app.listen(3000);
  console.log('Server running on http://localhost:3000');
}

bootstrap();
