Comandos instalação:
Back:
--Dependências:
   composer install
   cp .env.example .env
   php artisan key:generate
   composer require laravel/sanctum
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate

-- Iniciar projeto: 
   php artisan serve

Front: 

-- Dependências:
   npm install

-- Inicirar Projeto: 
   npm run dev
