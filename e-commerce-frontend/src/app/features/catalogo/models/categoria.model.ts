export interface Categoria {
  id_categoria: number;
  nombre:       string;
  descripcion:  string | null;
  tipo:         'producto' | 'servicio' | 'mixto';
  estado:       'activo' | 'inactivo';
}

export interface CategoriaForm {
  nombre:       string;
  descripcion:  string;
  tipo:         'producto' | 'servicio' | 'mixto';
  estado:       'activo' | 'inactivo';
}

export interface CategoriaResponse {
  data:  Categoria[];
  total: number;
}