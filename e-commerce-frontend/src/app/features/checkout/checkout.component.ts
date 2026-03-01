import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import { NavbarComponent } from '../../shared/navbar/navbar.component';
import { CartService } from '../cart/services/cart.service';
import { CartItem } from '../cart/models/cart.model';

@Component({
  selector: 'app-checkout',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, NavbarComponent],
  templateUrl: './checkout.component.html',
  styleUrls: ['./checkout.component.scss']
})
export class CheckoutComponent {
  cart    = inject(CartService);
  fb      = inject(FormBuilder);
  router  = inject(Router);

  step       = signal<1 | 2 | 3>(1); // 1=datos, 2=resumen, 3=confirmado
  submitting = signal(false);

  form = this.fb.group({
    nombre:    ['', Validators.required],
    email:     ['', [Validators.required, Validators.email]],
    telefono:  ['', Validators.required],
    direccion: ['', Validators.required],
    ciudad:    ['', Validators.required],
    notas:     [''],
    metodo_pago: ['transferencia', Validators.required],
  });

  constructor() {
    // Si el carrito está vacío, redirigir
    if (this.cart.estaVacio()) {
      this.router.navigate(['/dashboard/productos']);
    }
  }

  irResumen() {
    if (this.form.invalid) { this.form.markAllAsTouched(); return; }
    this.step.set(2);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  confirmarPedido() {
    this.submitting.set(true);
    // Simular envío (aquí irá POST /api/pedidos cuando esté el backend)
    setTimeout(() => {
      this.cart.vaciar();
      this.step.set(3);
      this.submitting.set(false);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }, 1500);
  }

  volverTienda() {
    this.router.navigate(['/dashboard/productos']);
  }

  getImagen(ci: CartItem): string {
    const imgs = [
      'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=200&q=80',
      'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?w=200&q=80',
      'https://images.unsplash.com/photo-1545205597-3d9d02c29597?w=200&q=80',
      'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=200&q=80',
      'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=200&q=80',
    ];
    return imgs[ci.item.id_item % imgs.length];
  }

  getMetodoPagoLabel(m: string): string {
    const map: Record<string, string> = {
      transferencia: 'Transferencia bancaria',
      efectivo:      'Pago en efectivo',
      tarjeta:       'Tarjeta de crédito/débito',
    };
    return map[m] ?? m;
  }

  f(key: string) { return this.form.get(key); }
}