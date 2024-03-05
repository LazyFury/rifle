
<template>
    <div class="btn" :style="{'user-select':selectable?'auto':'none'}">
        <slot></slot>
    </div>
</template>
<script>
export default {
    components: {},
    props: {
        selectable: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
        };
    },
    watch: {},
    computed: {},
    methods: {},
    created() { },
    mounted() { }
};
</script>
<style lang="scss" scoped>
.btn {
    cursor: pointer;
    transition: .3s;

    &:hover {
        opacity: .6;
    }
}
</style>
