import { writable } from 'svelte/store';

export const accessoriesList = writable([]);
export const availableAccessories = writable( [] );
export const modalRoot = writable(undefined)
