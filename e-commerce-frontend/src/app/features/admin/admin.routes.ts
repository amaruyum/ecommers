import { Routes } from '@angular/router';

export const adminRoutes: Routes = [
  {
    path: '',
    loadComponent: () =>
      import('../../shared/admin-layout/layout/layout.component').then(m => m.AdminLayoutComponent),
    children: [
      {
        path: '',
        loadComponent: () =>
          import('../../features/admin/dashboard/dashboard.component').then(m => m.AdminDashboardComponent)
      },
      {
        path: 'catalogo/categorias',
        loadComponent: () =>
          import('../../features/admin/categorias/categorias.component').then(m => m.AdminCategoriasComponent)
      },
      {
        path: 'catalogo/items',
        loadComponent: () =>
          import('../../features/admin/items/items.component').then(m => m.AdminItemsComponent)
      },
      { path: 'usuarios',
        loadComponent: () => 
            import('../../features/admin/usuarios/usuarios.component').then(m => m.AdminUsuariosComponent)
      },
      { path: 'contenido/blog',
        loadComponent: () => 
          import('../../features/admin/contenido/contenido.component').then(m => m.AdminContenidoComponent) },
    ]
  }
];