import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { AuthService } from '../../services/auth.services';


@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterLink],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  private fb      = inject(FormBuilder);
  private auth    = inject(AuthService);
  private router  = inject(Router);

  showPassword = signal(false);
  loading      = signal(false);
  errorMsg     = signal('');

  form = this.fb.group({
    correo_electronico: ['', [Validators.required, Validators.email]],
    contrasena:         ['', [Validators.required, Validators.minLength(8)]],
    recuerdame:         [false]
  });

  togglePassword() {
    this.showPassword.update(v => !v);
  }

  onSubmit() {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    this.loading.set(true);
    this.errorMsg.set('');

    const { correo_electronico, contrasena } = this.form.value;

    this.auth.login({ correo_electronico: correo_electronico!, contrasena: contrasena! })
      .subscribe({
        next: () => {
          this.loading.set(false);
          this.router.navigate(['/dashboard']);
        },
        error: (err) => {
          this.loading.set(false);
          this.errorMsg.set(
            err.status === 401
              ? 'Correo o contraseña incorrectos'
              : 'Error al iniciar sesión. Intenta de nuevo.'
          );
        }
      });
  }

  get email() { return this.form.get('correo_electronico'); }
  get password() { return this.form.get('contrasena'); }
}