import {writable} from 'svelte/store';

export const currentListKey = writable<string>('');
export const currentListId = writable<string>('');