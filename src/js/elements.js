import { createApp } from 'vue';

import 'element-plus/dist/index.css';   
import {Upload, Top, Bottom, Edit, Delete, View, Tools, Back, Plus, CircleCheck, SuccessFilled, CoffeeCup} from '@element-plus/icons-vue'

const elPlusIcons = [
    Top,
    Bottom,
    Edit,
    View,
    Delete,
    Tools,
    Back,
    Upload,
    Plus,
    CircleCheck,
    SuccessFilled,
    CoffeeCup
];


const app = createApp({});
app.use(ElLoading);
app.use(ElIcon);

elPlusIcons.forEach(component => {
    app.component(component.name, component)
})


export default app;
