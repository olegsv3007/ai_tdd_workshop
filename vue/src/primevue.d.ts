declare module 'primevue/toastservice' {
  import { App, Plugin } from 'vue';
  
  export interface ToastServiceMethods {
    add(toast: {
      severity?: string;
      summary?: string;
      detail?: string;
      life?: number;
      group?: string;
      position?: string;
      closable?: boolean;
      data?: any;
    }): void;
    removeGroup(group: string): void;
    removeAllGroups(): void;
  }
  
  declare const _default: {
    install: (app: App) => void;
  };
  
  export default _default;
}
