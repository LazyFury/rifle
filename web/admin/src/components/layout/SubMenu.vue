<template>
    <el-sub-menu
        v-if="item?.children && item?.children?.length > 0"
        :key="item.key"
        :index="item.key"
    >
        <template #title>
            <Icon :icon="item.icon"></Icon>
            <span class="ml-1">{{ $t(item.title) }}</span>
        </template>
        <template v-for="childItem in item.children">
            <MyMenuItem v-if="!hasChildren(childItem)" :childItem="childItem">
            </MyMenuItem>
            <template v-else>
                <!-- el-menu-item-group  -->
                <ElMenuItemGroup
                    v-if="childItem.type == 'group'"
                    class="!bg-gray-100"
                >
                    <template #title>
                        <Icon :icon="childItem.icon"></Icon>
                        <span class="ml-1">{{ $t(childItem.title) }}</span>
                    </template>
                    <template v-for="three in childItem.children">
                        <MyMenuItem
                            :childItem="three"
                            class="!pl-14 bg-gray-50 text-gray"
                        ></MyMenuItem>
                    </template>
                </ElMenuItemGroup>
                <SubMenu v-else :item="childItem"></SubMenu>
            </template>
        </template>
    </el-sub-menu>
</template>

<script>
import router from "@/router";
import MenuItem from "./MenuItem.vue";
import { ElMenuItemGroup } from "element-plus";
export default {
    name: "SubMenu",
    components: {
        MyMenuItem: MenuItem,
    },
    props: {
        item: {
            type: Object,
            default: () => {},
        },
    },
    computed: {},
    methods: {
        to(path) {
            router.push(path);
        },
        hasChildren(item) {
            return item?.children && item?.children?.length > 0;
        },
    },
};
</script>
