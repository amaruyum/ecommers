export type TipoContenido = 'articulo' | 'noticia' | 'video' | 'anuncio';

export interface Contenido {
  id_contenido:     number;
  id_administrador: number;
  titulo:           string;
  cuerpo:           string;
  fecha_creacion:   string;
  tipo_contenido:   TipoContenido;
}

export interface ContenidoResponse {
  data:  Contenido[];
  total: number;
}