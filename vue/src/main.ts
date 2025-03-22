import { createApp } from "vue";
import App from "./App.vue";
import PrimeVue from "primevue/config";
import ToastService from "primevue/toastservice";

// Import PrimeVue theme preset
import Aura from '@primeuix/themes/aura';

// Import Inter font used in PrimeVue 3
import "./assets/fonts/inter.css";

// Import PrimeVue components
import Button from 'primevue/button';
import Card from 'primevue/card';
import ProgressSpinner from 'primevue/progressspinner';
import Avatar from 'primevue/avatar';
import Badge from 'primevue/badge';
import Toast from 'primevue/toast';
import Tag from 'primevue/tag';

// Import PrimeIcons CSS
import "primeicons/primeicons.css";

// Import PrimeFlex for layout utilities
import "/node_modules/primeflex/primeflex.css";

// Create Vue app
const app = createApp(App);

// Register PrimeVue components
app.component('Button', Button);
app.component('Card', Card);
app.component('ProgressSpinner', ProgressSpinner);
app.component('Avatar', Avatar);
app.component('Badge', Badge);
app.component('Toast', Toast);
app.component('Tag', Tag);

// Use PrimeVue with Aura theme
app.use(PrimeVue, {
  ripple: true,
  theme: {
    preset: Aura,
    options: {
      prefix: 'p',
      darkModeSelector: 'false',
      cssLayer: false
    }
  }
});
app.use(ToastService);

// Mount the app
app.mount("#app");
