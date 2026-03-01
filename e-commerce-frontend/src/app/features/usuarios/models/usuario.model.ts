export interface UsuarioCliente {
  id_usuario:   number;
  ciudad:       string | null;
  direccion:    string | null;
  preferencias: string | null;
}

export interface UsuarioInstructor {
  id_instructor:        number;
  id_usuario:           number;
  especialidad:         string | null;
  descripcion_perfil: string | null;
}

export type RolUsuario = 'cliente' | 'instructor' | 'admin' | 'usuario';

export interface Usuario {
  id_usuario:         number;
  nombre_completo:    string;
  correo_electronico: string;
  telefono:           string | null;
  estado_cuenta:      'activo' | 'inactivo' | 'suspendido';
  fecha_registro:     string | null;
  roles:              RolUsuario[];
  cliente?:           UsuarioCliente | null;
  instructor?:        UsuarioInstructor | null;
}

export interface UsuarioResponse {
  data:  Usuario[];
  total: number;
}