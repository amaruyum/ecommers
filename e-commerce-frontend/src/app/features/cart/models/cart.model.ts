import { Item } from "../../catalogo/models/item.model";


export interface CartItem {
  item:     Item;
  cantidad: number;
}