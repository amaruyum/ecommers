export interface ItemProducto {
  id_item:           number;
  marca:             string | null;
  fecha_elaboracion: string | null;
  fecha_caducidad:   string | null;
  stock_disponible:  number;
}

export interface ItemServicio {
  id_item:                number;
  id_instructor:          number | null;
  id_sede:                number | null;
  tipo_servicio:          string | null;
  fecha_inicio:           string | null;
  fecha_fin:              string | null;
  itinerario:             string | null;
  lugar:                  string | null;
  cupos_totales:          number;
  cupos_disponibles:      number;
  politicas_cancelacion:  string | null;
}

export interface Item {
  id_item:      number;
  id_categoria: number;
  nombre:       string;
  descripcion:  string | null;
  estado:       'activo' | 'inactivo' | 'agotado';
  precio:       number;
  tipo:         'producto' | 'servicio';
  categoria?:   { id_categoria: number; nombre: string };
  producto?:    ItemProducto | null;
  servicio?:    ItemServicio | null;
}

export interface ItemResponse {
  data:  Item[];
  total: number;
}