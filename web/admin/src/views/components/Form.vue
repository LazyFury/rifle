<template>
    <div>
        <div class="title">
            <span class="text-2xl">{{ formTitle }}{{ title }}</span>
        </div>
        <!-- {{ form }} -->
        <ElForm ref="formRef" :inline="false" :model="form" :rules="rules" :label-width="120" class="mt-0">
            <div class="mb-4 grid xl:grid-cols-2">
                <div v-for="field in fields" v-if="!multiRowMode">
                    <span>not support yet!</span>
                </div>
            </div>


            <div v-if="multiRowMode" v-for="items in fields" class="flex flex-row flex-wrap">
                <div class="w-line mb-2"></div>
                <template v-for="field in items" :key="field.name">
                    <ElFormItem v-if="!field.hidden" :label="field.label + ':'" :prop="field.name" :style="{
                        width: field.width
                    }">
                        <slot :name="field.name" :fields="fields" :field="field" :form="form">
                            <FormItem :field="field" v-model="form[field.name]"></FormItem>
                        </slot>
                    </ElFormItem>
                </template>
            </div>
        </ElForm>

        <div class="flex flex-row items-center justify-end">
            <ElButton @click="handleSubmit" type="primary" class="w-24">保存</ElButton>
        </div>
    </div>
</template>
<script>
import FormItem from './FormItem.vue'
export default {
    components: { FormItem },
    props: {
        fields: {
            type: Array,
            default: () => []
        },
        title:{
            type: String,
            default: ''
        },
        defaultForm: {
            type: Object,
            default: () => ({})
        },
        progressFormData: {
            type: Function,
            default: (res) => res
        }
    },
    computed: {
        rules() {
            // check required in fields 
            let map = {}
            this.flatFields.forEach(el => {
                if (el.required) {
                    map[el.name] = [
                        { required: true, message: el.placeholder }
                    ]
                }
            })
            return map;
        },
        flatFields() {
            if (!this.multiRowMode) return this.fields;
            return this.fields.reduce((acc, cur) => {
                return acc.concat(cur)
            }, [])
        },
        multiRowMode() {
            return this.fields && this.fields[0] && Array.isArray(this.fields[0])
        },
        isAdd() {
            return !this.form.id
        },
        formTitle() {
            return this.isAdd ? '新增' : '编辑'
        }
    },
    data() {
        return {
            form: {

            }
        };
    },
    watch: {},
    methods: {
        handleSubmit() {
            this.$refs.formRef.validate((valid) => {
                if (valid) {
                    this.$emit('submit', this.form)
                }
            })
        },
        edit(data) {
            this.reset()
            let form = JSON.parse(JSON.stringify(data))
            if(this.progressFormData && typeof this.progressFormData === 'function'){
                form = this.progressFormData(form)
            }
            this.form = form
            this.$forceUpdate()
        },
        add() {
            this.reset()
            this.$forceUpdate()
        },
        close() {
            this.reset()
        },
        reset() {
            this.$refs.formRef.resetFields()
            let form = JSON.parse(JSON.stringify(this.defaultForm))
            this.form = form
        }
    },
    created() { },
    mounted() { }
};
</script>
<style lang="scss" scoped></style>