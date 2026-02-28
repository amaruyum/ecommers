import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators, AbstractControl } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { AuthService } from '../../services/auth.services';


function passwordsMatch(control: AbstractControl) {
  const pass    = control.get('contrasena');
  const confirm = control.get('contrasena_confirmation');
  if (pass && confirm && pass.value !== confirm.value) {
    confirm.setErrors({ mismatch: true });
  } else {
    if (confirm?.errors?.['mismatch']) {
      confirm.setErrors(null);
    }
  }
  return null;
}

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterLink],
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent {
  private fb     = inject(FormBuilder);
  private auth   = inject(AuthService);
  private router = inject(Router);

  showPassword        = signal(false);
  showConfirmPassword = signal(false);
  loading             = signal(false);
  errorMsg            = signal('');
  successMsg          = signal('');

  // ── Métodos para el template ──────────────────────────
  togglePassword()        { this.showPassword.set(!this.showPassword()); }
  toggleConfirmPassword() { this.showConfirmPassword.set(!this.showConfirmPassword()); }

  form = this.fb.group({
    nombre_completo:         ['', [Validators.required, Validators.minLength(3)]],
    correo_electronico:      ['', [Validators.required, Validators.email]],
    telefono:                [''],
    contrasena:              ['', [Validators.required, Validators.minLength(8)]],
    contrasena_confirmation: ['', Validators.required],
  }, { validators: passwordsMatch });

  onSubmit() {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    this.loading.set(true);
    this.errorMsg.set('');

    const { nombre_completo, correo_electronico, telefono, contrasena, contrasena_confirmation } = this.form.value;

    this.auth.register({
      nombre_completo:         nombre_completo!,
      correo_electronico:      correo_electronico!,
      telefono:                telefono || undefined,
      contrasena:              contrasena!,
      contrasena_confirmation: contrasena_confirmation!,
    }).subscribe({
      next: () => {
        this.loading.set(false);
        this.successMsg.set('¡Cuenta creada! Redirigiendo...');
        setTimeout(() => this.router.navigate(['/auth/login']), 1500);
      },
      error: (err) => {
        this.loading.set(false);
        if (err.status === 422 && err.error?.errors) {
          const msgs = Object.values(err.error.errors).flat().join(', ');
          this.errorMsg.set(msgs as string);
        } else {
          this.errorMsg.set('Error al crear la cuenta. Intenta de nuevo.');
        }
      }
    });
  }

  get nombre()   { return this.form.get('nombre_completo'); }
  get email()    { return this.form.get('correo_electronico'); }
  get telefono() { return this.form.get('telefono'); }
  get password() { return this.form.get('contrasena'); }
  get confirm()  { return this.form.get('contrasena_confirmation'); }
}