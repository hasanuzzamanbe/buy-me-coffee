<template>
    <div class="wpm_bmc_main_container">
        <h1 class="wpm_bmc_menu_title">Supporters:</h1>
        <el-table
            :data="supporters"
            style="width: 100%">
            <el-table-column
                 width="180"
                label="Date">
                <template slot-scope="scope">
                    <span>{{ scope.row.created_at }}</span>
                </template>
            </el-table-column>
            <el-table-column
                width="180"
                label="Name">
                <template slot-scope="scope">
                    <span>{{ scope.row.supporters_name }}</span>
                </template>
            </el-table-column>
            <el-table-column
                label="Amount">
                <template slot-scope="scope">
                    <span v-html="scope.row.amount_formatted"></span>
                </template>
            </el-table-column>
            <el-table-column
                label="Status">
                <template slot-scope="scope">
                    <span style="margin-left: 10px">{{ scope.row.payment_status }}</span>
                </template>
            </el-table-column>
            <el-table-column
                label="Payment By">
                <template slot-scope="scope">
                    <el-tag size="small" style="margin-left: 10px">{{ scope.row.payment_method }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                label="Mode">
                <template slot-scope="scope">
                    <span style="margin-left: 10px">{{ scope.row.payment_mode ? scope.row.payment_mode : '-' }}</span>
                </template>
            </el-table-column>
            <el-table-column
                label="Operations">
            <template slot-scope="scope">
                <el-button-group>
                    <el-button
                    size="mini"
                    icon="el-icon-view"
                    @click="handleEdit(scope.row.id)"></el-button>
                <el-button
                    size="mini"
                    type="danger"
                    icon="el-icon-delete"
                    @click="handleDelete(scope.row.id)"></el-button>
                </el-button-group>
            </template>
            </el-table-column>
        </el-table>
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
            supporters: [{
                created_at: '2016-05-03',
                payment_method: 'PayPal',

                }, {
                created_at: '2016-05-02',
                payment_method: 'Stripe',

            }]
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
                this.$message({
                    type: 'success',
                    message: 'This record has been deleted.'
                })
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: 'This record is safe!'
                })
            })
        }
    },
    mounted() {
        this.getSupporters();
    }
}
</script>

