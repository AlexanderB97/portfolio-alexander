# Portfolio — Alexander

Portfolio profesional desarrollado como sistema real, no como landing estática. Cada proyecto se presenta como una "ficha de sistema", en línea con el tipo de aplicaciones que construyo: sistemas de gestión para negocios reales.

## Stack

- Laravel 12
- Livewire 3 + Volt
- Tailwind CSS
- MySQL

## Requisitos

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL (XAMPP)

## Setup local

```bash
git clone <url-del-repo>
cd portfolio-alexander
composer install
cp .env.example .env
php artisan key:generate
npm install
php artisan migrate
npm run dev
```

En otra terminal, levantar el servidor:

```bash
php artisan serve
```

## Estructura de ramas

- `main` — rama estable, protegida
- `epic/*` — una rama por épica de trabajo, con PR antes de mergear

## Roadmap por épicas

- [ ] `epic/scaffold-inicial` — instalación base Laravel + Livewire + Volt + Tailwind
- [ ] `epic/tema-visual-fichas` — maquetación del diseño "ficha de sistema"
- [ ] `epic/proyectos-dinamicos` — mover las fichas de proyecto a base de datos (Livewire + admin)
- [ ] `epic/deploy` — despliegue a producción

## Convenciones

- Commits en formato [Conventional Commits](https://www.conventionalcommits.org/) (`feat:`, `fix:`, `chore:`, `refactor:`)
- Un PR por épica, con revisión antes de mergear a `main`