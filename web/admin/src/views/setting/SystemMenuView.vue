<template>
<div>
    <ElCard :shadow="false">
        <div class="flex flex-row items-center justify-between">
            <div class="flex flex-row items-center">
                <Icon icon="ant-design:setting-outlined"></Icon>
                <span class="ml-2">系统菜单</span>
            </div>
            <div class="flex flex-row items-center">
                <ElButton type="primary">
                    <Icon icon="ant-design:plus-outlined"></Icon>
                    <span>添加</span>
                </ElButton>
            </div>
        </div>
        <div class="mt-4">
            <ElTable :data="tableData" border stripe>
                <ElTableColumn type="index" label="序号" width="60"></ElTableColumn>
                <ElTableColumn prop="name" label="菜单名称"></ElTableColumn>
                <ElTableColumn prop="desc" label="菜单描述"></ElTableColumn>
                <ElTableColumn prop="status" label="状态"></ElTableColumn>
                <ElTableColumn prop="action" label="操作">
                    <template #default="{row}">
                        <UIButton size="mini" type="primary" class="mr-2">编辑</UIButton>
                        <UIButton size="mini" type="danger">删除</UIButton>
                    </template>
                </ElTableColumn>
            </ElTable>
        </div>
    </ElCard>
</div>
</template>
<script>
export default {
  components: {},
  props: {},
  data() {
    return {
    };
  },
  watch: {},
  computed: {},
  methods: {},
  created() {},
  mounted() {}
};
</script>
<style lang="scss" scoped>
</style>