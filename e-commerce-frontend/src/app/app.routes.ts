import { Routes } from '@angular/router';
import { adminGuard, authGuard } from './core/auth.guard';

export const routes: Routes = [
    {
        path: '',
        redirectTo: 'auth/login',
        pathMatch: 'full'
    },
    {
        path: 'auth',
        loadChildren: () =>
            import('./features/auth/auth.routes').then(m => m.authRoutes)
    },
    {
        path: 'dashboard',
        canActivate: [authGuard],
        loadComponent: () =>
            import('./features/dashboard/dashboard.component').then(m => m.DashboardComponent)
    },
    {
        path: 'dashboard/perfil',
        canActivate: [authGuard],
        loadComponent: () =>
        import('./features/perfil/perfil.component').then(m => m.PerfilComponent)
    },
    {
        path: 'dashboard/retiros',
        canActivate: [authGuard],
        loadComponent: () => import('./features/retiros/retiros.component').then(m => m.RetirosComponent)
    },
    {
        path: 'dashboard/productos',
        canActivate: [authGuard],
        loadComponent: () => import('./features/productos/productos.component').then(m => m.ProductosComponent)
    },
    {
        path: 'dashboard/capacitaciones',
        canActivate: [authGuard],
        loadComponent: () => import('./features/capacitaciones/capacitaciones.component').then(m => m.CapacitacionesComponent)
    },
    {
        path: 'dashboard/checkout',
        canActivate: [authGuard],
        loadComponent: () =>
            import('./features/checkout/checkout.component').then(m => m.CheckoutComponent)
    },
    { 
        path: 'dashboard/blog', 
        canActivate: [authGuard],
        loadComponent: () => import('./features/blog/blog.component').then(m => m.BlogComponent) },
    {
        path: 'dashboard/item/:id',
        canActivate: [authGuard],
        loadComponent: () => import('./features/item-detalle/item-detalle.component').then(m => m.ItemDetalleComponent)
    },
    {
        path: 'admin',
        canActivate: [adminGuard],
        loadChildren: () => import('./features/admin/admin.routes').then(m => m.adminRoutes)
  },
    {
        path: '**',
        redirectTo: 'auth/login'
    }
];
