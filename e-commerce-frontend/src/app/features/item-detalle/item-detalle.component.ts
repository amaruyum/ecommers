import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { NavbarComponent } from '../../shared/navbar/navbar.component';
import { ItemService } from '../../features/catalogo/services/item.service';
import { Item } from '../../features/catalogo/models/item.model';
import { CartService } from '../cart/services/cart.service';


@Component({
  selector: 'app-item-detalle',
  standalone: true,
  imports: [CommonModule, RouterLink, NavbarComponent],
  templateUrl: './item-detalle.component.html',
  styleUrls: ['./item-detalle.component.scss']
})
export class ItemDetalleComponent implements OnInit {
  private route       = inject(ActivatedRoute);
  private router      = inject(Router);
  private itemService = inject(ItemService);
  cart                 = inject(CartService);

  item     = signal<Item | null>(null);
  loading  = signal(true);
  errorMsg = signal('');
  cantidad = signal(1);

  ngOnInit() {
    const id = Number(this.route.snapshot.paramMap.get('id'));
    if (!id) { this.router.navigate(['/dashboard']); return; }
    this.loadItem(id);
  }

  loadItem(id: number) {
    this.loading.set(true);
    this.itemService.getById(id).subscribe({
      next: item => { this.item.set(item); this.loading.set(false); },
      error: ()   => { this.errorMsg.set('Item no encontrado'); this.loading.set(false); }
    });
  }

  incrementar() { if (this.cantidad() < 99) this.cantidad.update(c => c + 1); }
  decrementar() { if (this.cantidad() > 1)  this.cantidad.update(c => c - 1); }

  isProducto() { return this.item()?.tipo === 'producto'; }
  isServicio()  { return this.item()?.tipo === 'servicio'; }

  getTotal(): number { return (this.item()?.precio ?? 0) * this.cantidad(); }

  formatFecha(fecha: string | null): string {
    if (!fecha) return '—';
    return new Date(fecha).toLocaleDateString('es-EC', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
  }

  getDuracion(): string {
    const s = this.item()?.servicio;
    if (!s?.fecha_inicio || !s?.fecha_fin) return '—';
    const inicio = new Date(s.fecha_inicio);
    const fin    = new Date(s.fecha_fin);
    const horas  = Math.round((fin.getTime() - inicio.getTime()) / (1000 * 60 * 60));
    if (horas < 24) return `${horas} horas`;
    const dias = Math.ceil(horas / 24);
    return `${dias} día${dias > 1 ? 's' : ''}`;
  }

  getCuposPct(): number {
    const s = this.item()?.servicio;
    if (!s?.cupos_totales) return 0;
    return Math.round(((s.cupos_disponibles ?? 0) / s.cupos_totales) * 100);
  }

  getImagen(): string {
    const item = this.item();
    if (!item) return '';
    const imgs = [
      'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=1200&q=80',
      'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?w=1200&q=80',
      'https://images.unsplash.com/photo-1545205597-3d9d02c29597?w=1200&q=80',
      'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1200&q=80',
    ];
    return imgs[item.id_item % imgs.length];
  }

  volver() { window.history.back(); }

  agregarAlCarrito() {
    const item = this.item();
    if (!item) return;
    this.cart.agregar(item, this.cantidad());
  }
}