<template>
    <div>
        <!-- example content  start -->
        <el-container>
            <el-header>
                <el-row>
                    <el-col :span="12">
                        <h1>Buy Me <i class="el-icon-coffee" ></i>- Button templates</h1>
                    </el-col>
                    <el-col :span="12" style="padding: 12px;">
                        <div>
                            <el-button style="float:right" icon="el-icon-plus" type="primary" size="mini" click="addNew">Add new</el-button>
                        </div>
                    </el-col>
                </el-row>
            </el-header>
            <el-main>
                <el-row class="wpm-template">
                    <div class="wpm-template-inner"
                        v-for="template in templates"
                        :key="template.id">
                        <div class="wpm-template-top">
                            <h2>
                                {{ template.title }}
                            </h2>
                            <p>supports: {{template.supports}}</p>
                            <div style="margin-bottom:20px;">
                                <el-tag
                                    v-for="tag in template.methods"
                                    :key="tag"
                                    size="mini"
                                    type="info"
                                    effect="light">
                                    {{tag}}
                                </el-tag>
                            </div>
                            <el-tooltip effect="dark"
                                        content="Click to copy shortcode"
                                        title="Click to copy shortcode"
                                        placement="top">
                                <code class="copy"
                                      :data-clipboard-text='`[buymecoffee id="${template.id}"]`'>
                                    <i class="el-icon-document"></i> [buymecoffee id="{{ template.id }}"]
                                </code>
                            </el-tooltip>
                        </div>
                        <div class="wpm-template-bottom">
                            <el-button-group style="float:right">
                                <el-button size="mini" plain type="warning" @click="preview(template.id)">
                                    <i class="el-icon-edit"></i>
                                </el-button>
                                <el-button size="mini" plain type="primary" @click="preview(template.id)">
                                    <i class="el-icon-view"></i>
                                </el-button>
                            </el-button-group>
                        </div>
                    </div>
                </el-row>
            </el-main>
        </el-container>
        <!-- example content end -->
    </div>
</template>
<script>
import Clipboard from 'clipboard';
export default {
    name: 'Dashboard',
    data(){
        return {
            vueJs: 'https://vuejs.org/',
            templates: [
                {
                    id: '1',
                    title: 'Yellow Button',
                    description: 'A yellow button',
                    supports: 3,
                    methods: ['stripe', 'paypal'],
                    created_at: '2022-01-01',
                    updated_at: '2022-01-01',
                },
                {
                    id: '2',
                    title: 'Blue Button',
                    description: 'A blue button',
                    supports: 2,
                    methods: ['stripe', 'paypal'],
                    created_at: '2022-01-01',
                    updated_at: '2022-01-01',
                },
                {
                    id: '3',
                    title: 'Green Button',
                    description: 'A green button',
                    supports: 1,
                    methods: ['stripe', 'paypal'],
                    created_at: '2022-01-01',
                    updated_at: '2022-01-01',
                },
                {
                    id: '4',
                    title: 'Red Button',
                    description: 'A red button',
                    supports: 0,
                    methods: ['stripe', 'paypal'],
                    created_at: '2022-01-01',
                    updated_at: '2022-01-01',
                }
            ]
        }
    },
    methods: {
        preview(id){
            console.log(id);
        },
        addNew(){
            console.log('add new');
        }
    },
    mounted() {
        if(!window.wpm_clip_inited) {
            var clipboard = new Clipboard('.copy');
            clipboard.on('success', (e) => {
                this.$message({
                    message: 'Copied to Clipboard!',
                    type: 'success',
                    offset: 32
                });
            });
            window.wpm_clip_inited = true;
        }
    }
}
</script>

