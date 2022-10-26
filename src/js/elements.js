import { createApp } from 'vue';

import {
    ElRow,
    ElCol,
    ElTable,
    ElTableColumn,
    ElButtonGroup,
    ElButton,
    ElForm,
    ElFormItem,
    ElInput,
    ElPagination,
    ElLoading,
    ElMessage,
    ElNotification
} from 'element-plus';

const app = createApp({});

const components = [
    ElButton,
    ElButtonGroup,
    ElTable,
    ElTableColumn,
    ElInput,
    ElForm,
    ElFormItem,
    ElRow,
    ElCol,
    ElPagination,
];

components.forEach(component => {
    app.component(component.name, component)
})

const plugins = [
    components,
    ElLoading,
    ElNotification
];

plugins.forEach(plugin => {
    app.use(plugin)
});

export default app;
