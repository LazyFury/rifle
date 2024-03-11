<template>
    <TableView>
        <template #edit_permission="{ row }">
            <UIButton class="text-primary flex flex-row items-center" @click="editPermission(row.id)" type="text"
                size="mini">
                <span> 编辑角色权限</span>
                <Icon icon="ant-design:edit-outlined"></Icon>
            </UIButton>
        </template>
    </TableView>
</template>


<script>
import TableView from '@/views/TableView.vue';
export default {
    components: { TableView },
    setup() {

    }
}
</script>