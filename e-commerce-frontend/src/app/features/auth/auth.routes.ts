import { Routes } from "@angular/router";
import { LoginComponent } from "./pages/login/login.component";
import { RegisterComponent } from "./pages/register/register.component";

export const authRoutes: Routes = [
    {
        path: '',
        redirectTo: 'login',
        pathMatch: 'full'
    },
    { path: 'login',
        loadComponent: () =>
            import('./pages/login/login.component').then(m => m.LoginComponent)
    },
    { path: 'register',
        loadComponent: () => 
            import('./pages/register/register.component').then(m => m.RegisterComponent)
    },
];