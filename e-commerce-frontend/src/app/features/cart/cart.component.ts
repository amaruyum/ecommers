import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { CartService } from './services/cart.service';
import { CartItem } from './models/cart.model';


@Component({
  selector: 'app-cart-drawer',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './cart.component.html',
  styleUrls: ['./cart.component.scss']
})
export class CartDrawerComponent {
  cart   = inject(CartService);
  router = inject(Router);

  incrementar(ci: CartItem) {
    this.cart.actualizarCantidad(ci.item.id_item, ci.cantidad + 1);
  }

  decrementar(ci: CartItem) {
    this.cart.actualizarCantidad(ci.item.id_item, ci.cantidad - 1);
  }

  quitar(ci: CartItem) {
    this.cart.quitar(ci.item.id_item);
  }

  irCheckout() {
    this.cart.cerrarDrawer();
    this.router.navigate(['/dashboard/checkout']);
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
}