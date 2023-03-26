<template>
    <div class="wpm_bmc_main_container">
        <h1 class="wpm_bmc_menu_title">Supporters:</h1>
        <el-table
            :data="supporters"
            style="width: 100%">
            <el-table-column
                 width="180"
                label="Date">
                <template #default="scope">
                    <span>{{ scope.row.created_at }}</span>
                </template>
            </el-table-column>
            <el-table-column
                prop="supporters_name"
                width="180"
                label="Name">
                <template #default="scope">
                    <span>{{ scope.row.supporters_name }}</span>
                </template>
            </el-table-column>
            <el-table-column
                label="Amount">
                <template #default="scope">
                    <span v-html="scope.row.amount_formatted"></span>
                </template>
            </el-table-column>
            <el-table-column
                prop="payment_status"
                label="Status">
            </el-table-column>
            <el-table-column
                prop="payment_method"
                label="Payment By">
            </el-table-column>
            <el-table-column
                label="Mode">
                <template #default="scope">
                    <span style="margin-left: 10px">{{ scope.row.payment_mode ? scope.row.payment_mode : '-' }}</span>
                </template>
            </el-table-column>
            <el-table-column
                label="Operations">
            <template #default="scope">
                <el-button-group>
                    <el-button
                    size="default"
                    icon="View"
                    @click="handleEdit(scope.row.id)"></el-button>
                <el-button
                    size="default"
                    type="danger"
                    icon="Delete"
                    @click="handleDelete(scope.row.id)"></el-button>
                </el-button-group>
            </template>
            </el-table-column>
        </el-table>
        <br/>
        <el-pagination
            @current-change="handleSizeChange"
            :page-size="posts_per_page"
            background
            layout="prev, pager, next"
            :total="total">
        </el-pagination>
    </div>
</template>
<script>
export default {
    name: 'Supports',
    data() {
        return {
            limit: 20,
            posts_per_page: 20,
            current: 0,
            total: null,
            supporters: []
        }
    },
    methods: {
        handleSizeChange(val) {
            this.current = val-1;
            this.getSupporters();
        },
        getSupporters () {
            this.$get({
                action: 'wpm_bmc_admin_ajax',
                route: 'get_supporters',
                limit: this.limit,
                page: this.current,
                posts_per_page: this.posts_per_page,
            })
                .then((response) => {
                    this.supporters = response.data.supporters;
                    console.log(this.supporters)

                    this.total = response.data.total;
                })
                .fail(error => {
                    this.$message.error(error.responseJSON.data.message);
                })
                .always(() => {
                    this.fetching = false;
                });

        },
        handleEdit(id) {
            this.$post({
                action: 'wpm_bmc_admin_ajax',
                route: 'edit_supporter',
                id: id
            })
        },
        handleDelete(id) {
            this.$post({
                action: 'wpm_bmc_admin_ajax',
                route: 'delete_supporter',
                id: id
            }).then(() => {
                this.$handleSuccess('This record has been deleted.')
                this.getSupporters();
            }).catch((e) => {
                this.$handleError(e)
            })
        }
    },
    mounted() {
        this.getSupporters();
    }
}
</script>

