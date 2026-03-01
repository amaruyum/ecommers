import { Injectable, signal, computed, effect } from '@angular/core';

import { Item } from '../../catalogo/models/item.model';
import { CartItem } from '../models/cart.model';

const STORAGE_KEY = 'wellness_cart';

@Injectable({ providedIn: 'root' })
export class CartService {

  // ── Estado ────────────────────────────────────────────────
  items      = signal<CartItem[]>(this.cargarDesdeStorage());
  drawerOpen = signal(false);

  // ── Derivados ─────────────────────────────────────────────
  totalItems = computed(() =>
    this.items().reduce((acc, ci) => acc + ci.cantidad, 0)
  );

  subtotal = computed(() =>
    this.items().reduce((acc, ci) => acc + ci.item.precio * ci.cantidad, 0)
  );

  estaVacio = computed(() => this.items().length === 0);

  // ── Persistencia automática ───────────────────────────────
  constructor() {
    effect(() => {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(this.items()));
    });
  }

  private cargarDesdeStorage(): CartItem[] {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch {
      return [];
    }
  }

  // ── Acciones ──────────────────────────────────────────────
  agregar(item: Item, cantidad = 1): void {
    this.items.update(cart => {
      const idx = cart.findIndex(ci => ci.item.id_item === item.id_item);
      if (idx >= 0) {
        const copia = [...cart];
        copia[idx] = { ...copia[idx], cantidad: copia[idx].cantidad + cantidad };
        return copia;
      }
      return [...cart, { item, cantidad }];
    });
    this.drawerOpen.set(true);
  }

  quitar(id_item: number): void {
    this.items.update(cart => cart.filter(ci => ci.item.id_item !== id_item));
  }

  actualizarCantidad(id_item: number, cantidad: number): void {
    if (cantidad <= 0) { this.quitar(id_item); return; }
    this.items.update(cart =>
      cart.map(ci => ci.item.id_item === id_item ? { ...ci, cantidad } : ci)
    );
  }

  vaciar(): void { this.items.set([]); }

  abrirDrawer():  void { this.drawerOpen.set(true);  }
  cerrarDrawer(): void { this.drawerOpen.set(false); }

  estaEnCarrito(id_item: number): boolean {
    return this.items().some(ci => ci.item.id_item === id_item);
  }

  getCantidad(id_item: number): number {
    return this.items().find(ci => ci.item.id_item === id_item)?.cantidad ?? 0;
  }
}