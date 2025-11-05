export interface Country {
  nome: string
  fone: string
  iso: string
}

export interface Contact {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  cep: string | null;
  city: string | null;
  country: string | null;
  state: string | null;
  neighborhood: string | null;
  street_address: string | null;
  house_number: number | string | null;
  complement: string | null;
  picture: string | null;
  created_at: string;
  updated_at: string;
}

// 🔗 Estrutura dos links de paginação
export interface PaginationLink {
  url: string | null;
  label: string;
  page: number | null;
  active: boolean;
}

// 📄 Estrutura completa retornada pela API Laravel
export interface Paginator {
  current_page: number;
  data: Contact[];
  first_page_url: string;
  from: number | null;
  last_page: number;
  last_page_url: string;
  links: PaginationLink[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number | null;
  total: number;
}