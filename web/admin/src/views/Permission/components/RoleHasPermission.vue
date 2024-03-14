<template>
    <div>
        <ElDialog
            title="Role Has Permission"
            v-model="dialogVisible"
            width="640px"
        >
            <ElCard shadow="never" class="!mt-0 !pt-0">
                <div>
                    <div class="flex flex-row items-center gap-2">
                        <h3 class="!my-0">{{ role.name }}</h3>
                        <p>{{ role.remark }}</p>
                    </div>
                    <ElDivider class="!my-2"></ElDivider>
                    <div>
                        <!-- btn set all  -->
                        <ElButton
                            @click="handleSelectAll"
                            type="text"
                            size="mini"
                            >Set All</ElButton
                        >
                        <!-- btn reset all  -->
                        <ElButton
                            @click="handleResetAll()"
                            type="text"
                            size="mini"
                            >Reset All</ElButton
                        >
                        <!-- open all  -->
                        <ElButton type="text" size="mini">Open All</ElButton>
                        <!-- close all  -->
                        <ElButton type="text" size="mini">Close All</ElButton>
                    </div>
                </div>
                <div class="max-h-320px" style="overflow-y: auto">
                    <ElTree
                        ref="tree"
                        :data="treeData"
                        :props="defaultProps"
                        node-key="id"
                        show-checkbox
                    ></ElTree>
                </div>
            </ElCard>

            <template #footer>
                <ElButton @click="close" type="default">取消</ElButton>
                <ElButton @click="save" type="primary">保存</ElButton>
            </template>
        </ElDialog>
    </div>
</template>

<script>
import { request } from "@/api/request";
import { ElButton, ElDivider } from "element-plus";

export default {
    name: "RoleHasPermission",
    props: {},
    data() {
        return {
            defaultProps: {
                children: "children",
                label: "name",
            },
            dialogVisible: false,
            treeData: [
                {
                    id: 1,
                    name: "Level 1",
                    children: [
                        {
                            id: 4,
                            name: "Level 2-1",
                        },
                    ],
                },
            ],
            role: {},
            checkedKeys: [],
        };
    },
    methods: {
        handleSelectAll(def = true) {
            let flatArr = this.treeData.reduce((acc, cur) => {
                return acc.concat(cur, cur.children || []);
            }, []);
            this.$refs.tree.setCheckedKeys(
                flatArr.map((item) => item.id),
                def
            );
        },
        handleResetAll() {
            this.$refs.tree.setCheckedKeys([]);
        },
        open(row) {
            this.dialogVisible = true;
            this.role = row;
            this.getPermissions();
            this.getRolesPermissions();
        },
        close() {
            this.dialogVisible = false;
        },
        getRolesPermissions() {
            request
                .get("/role.get_permissions", {
                    params: {
                        role_id: this.role.id,
                    },
                })
                .then((res) => {
                    this.checkedKeys = (res.data.data || [])
                        .filter((v) => v.enabled)
                        .map((item) => item.permission_id);
                    this.$refs.tree.setCheckedKeys(this.checkedKeys);
                });
        },
        getPermissions() {
            request
                .get("/permission.list", {
                    params: {
                        page: 1,
                        limit: 999,
                    },
                })
                .then((res) => {
                    this.treeData = res.data.data?.data;
                });
        },
        save() {
            let allIds = this.treeData
                .reduce((acc, cur) => {
                    return acc.concat(cur, cur.children || []);
                }, [])
                .map((item) => item.id);
            let ids = this.$refs.tree.getCheckedKeys();
            console.log(ids);
            request
                .post("/role.set_permissions", {
                    role_id: this.role.id,
                    permissions: allIds.map((item) => ({
                        permission_id: item,
                        enabled: ids.includes(item) ? 1 : 0,
                    })),
                })
                .then(() => {
                    this.$message.success("保存成功");
                    this.close();
                });
        },
    },
};
</script>

<style lang="scss">
.el-card__body {
    padding-top: 10px !important;
}
</style>
