export interface LoginRequest {
  correo_electronico: string;
  contrasena: string;
}

export interface RegisterRequest {
  nombre_completo: string;
  correo_electronico: string;
  telefono?: string;
  contrasena: string;
  contrasena_confirmation: string;
}

export interface Usuario {
  id_usuario: number;
  nombre_completo: string;
  correo_electronico: string;
  telefono?: string;
  estado_cuenta: string;
  is_admin: boolean;
}

export interface AuthResponse {
  message: string;
  token?: string;
  usuario: Usuario;
}