<template>
  <div class="buymecoffee_email">
    <el-row>
      <el-col :span="12">
        <h1 class="buymecoffee_menu_title"></h1>
      </el-col>
      <el-col :span="12">
      </el-col>
    </el-row>
    <el-row>
      <el-table :data="tableData" style="width: 100%">
        <el-table-column prop="name" label="Name" width="180">
        </el-table-column>
        <el-table-column prop="trigger_on" label="Trigger On" width="180">
          <template #default="scope">
            <el-popover effect="light" trigger="hover" placement="top" width="auto">
              <template #default>
                <div>Event: {{ scope.row.trigger_on }}</div>
                <div>{{ scope.row.trigger_on === 'on_submit' ? 'This will trigger before the payment.' : 'Trigger only the payment paid!' }}</div>
              </template>
              <template #reference>
                <el-tag>{{ scope.row.trigger_on }}</el-tag>
              </template>
            </el-popover>
          </template>
        </el-table-column>
        <el-table-column label="Operations">
          <template #default="scope">
            <el-button size="small" @click="handleEdit(scope.$index, scope.row)">
              Edit
            </el-button>
            <el-button
                size="small"
                type="danger"
                @click="handleDelete(scope.$index, scope.row)"
            >
              Delete
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-row>
  </div>
</template>
<script>
export default {
  name: "Emails",
  data() {
    return {
      tableData: [
        {
          name: "John Brown",
          trigger_on: 'on_submit',
          address: "New York No. 1 Lake Park",
        },
        {
          name: "Jim Green",
          trigger_on: 'on_submit',
          address: "London No. 1 Lake Park",
        },
        {
          name: "Joe Black",
          trigger_on: 'on_payment_paid',
          address: "Sydney No. 1 Lake Park",
        },
        {
          name: "Jon Snow",
          trigger_on: 'on_payment_paid',
          address: "Ottawa No. 1 Lake Park",
        },
      ],
    };
  },
  methods: {
    handleEdit(index, row) {
      console.log(index, row);
    },
    handleDelete(index, row) {
      this.tableData.splice(index, 1);
    },
  }
}
</script>


<style scoped lang="scss">

</style>