import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { NavbarComponent } from '../../shared/navbar/navbar.component';
import { ItemService } from '../../features/catalogo/services/item.service';
import { CategoriaService } from '../../features/catalogo/services/categoria.service';
import { Item } from '../../features/catalogo/models/item.model';

import { Categoria } from '../../features/catalogo/models/categoria.model';
import { CartService } from '../cart/services/cart.service';

@Component({
  selector: 'app-productos',
  standalone: true,
  imports: [CommonModule, RouterLink, NavbarComponent],
  templateUrl: './productos.component.html',
  styleUrls: ['./productos.component.scss']
})
export class ProductosComponent implements OnInit {
  private itemService      = inject(ItemService);
  private categoriaService = inject(CategoriaService);
  cart                     = inject(CartService);

  productos   = signal<Item[]>([]);
  categorias  = signal<Categoria[]>([]);
  loading     = signal(true);
  errorMsg    = signal('');
  favoritos   = signal<number[]>([]);

  filterSearch    = signal('');
  filterCategoria = signal('');
  filterOrden     = signal('');

  ngOnInit() {
    this.loadProductos();
    this.loadCategorias();
  }

  loadProductos() {
    this.loading.set(true);
    this.itemService.getAll({
      tipo:         'producto',
      estado:       'activo',
      search:       this.filterSearch() || undefined,
      id_categoria: this.filterCategoria() ? +this.filterCategoria() : undefined,
    }).subscribe({
      next: res => {
        let data = res.data;
        if (this.filterOrden() === 'precio_asc')  data = [...data].sort((a, b) => a.precio - b.precio);
        if (this.filterOrden() === 'precio_desc') data = [...data].sort((a, b) => b.precio - a.precio);
        this.productos.set(data);
        this.loading.set(false);
      },
      error: () => { this.errorMsg.set('Error al cargar productos'); this.loading.set(false); }
    });
  }

  loadCategorias() {
    this.categoriaService.getAll({ estado: 'activo' }).subscribe({
      next: res => this.categorias.set(res.data)
    });
  }

  toggleFavorito(id: number) {
    this.favoritos.update(favs =>
      favs.includes(id) ? favs.filter(f => f !== id) : [...favs, id]
    );
  }

  esFavorito(id: number) { return this.favoritos().includes(id); }

  onSearch(e: Event)      { this.filterSearch.set((e.target as HTMLInputElement).value);  this.loadProductos(); }
  onFilterCat(e: Event)   { this.filterCategoria.set((e.target as HTMLSelectElement).value); this.loadProductos(); }
  onFilterOrden(e: Event) { this.filterOrden.set((e.target as HTMLSelectElement).value);  this.loadProductos(); }

  getImagen(item: Item): string {
    const imgs = [
      'https://images.unsplash.com/photo-1545205597-3d9d02c29597?w=600&q=80',
      'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&q=80',
      'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?w=600&q=80',
      'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&q=80',
    ];
    return imgs[item.id_item % imgs.length];
  }

  getStockLabel(item: Item): string {
    const stock = item.producto?.stock_disponible ?? 0;
    if (stock === 0) return 'Agotado';
    if (stock <= 5)  return `Últimas ${stock} unidades`;
    return `${stock} disponibles`;
  }

  isAgotado(item: Item): boolean {
    return (item.producto?.stock_disponible ?? 0) === 0;
  }

  agregarAlCarrito(item: Item, event: Event) {
    event.stopPropagation();
    event.preventDefault();
    this.cart.agregar(item, 1);
  }
}