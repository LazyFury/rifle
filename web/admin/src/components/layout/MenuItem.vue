<template>
    <el-menu-item :index="childItem.key" @click="to(childItem.path)">
        <Icon :icon="childItem.icon"></Icon>
        <span class="ml-1">{{ childItem.title }}</span>
    </el-menu-item>
</template>


<script>
import router from '@/router';
export default {
    name: 'MenuItem',
    components: {
    },
    props: {
        childItem: {
            type: Object,
            default: () => {
                return {}
            }
        }
    },
    methods: {
        to(path) {
            router.push(path)
        }
    }
}
</script>