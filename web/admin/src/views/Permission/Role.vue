<template>
    <TableView>
        <template #edit_permission="{ row }">
            <UIButton
                class="text-primary flex flex-row items-center"
                @click="editPermission(row)"
                type="text"
                size="mini"
            >
                <span> 编辑角色权限</span>
                <Icon icon="ant-design:edit-outlined"></Icon>
            </UIButton>
        </template>
    </TableView>
    <RoleHasPermission ref="roleHasPermission"></RoleHasPermission>
</template>

<script>
import TableView from "@/views/TableView.vue";
import RoleHasPermission from "./components/RoleHasPermission.vue";
export default {
    components: { TableView, RoleHasPermission },
    setup() {},
    methods: {
        editPermission(row) {
            this.$refs.roleHasPermission.open(row);
        },
    },
};
</script>
