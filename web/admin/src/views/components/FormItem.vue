<template>
    <!-- textarea  -->
    <ElInput @change="handleUpdate" v-model="value" v-if="field.type == 'textarea'" type="textarea"
        :placeholder="field.placeholder"></ElInput>
    <!-- password  -->
    <ElInput @change="handleUpdate" v-model="value" v-if="field.type == 'password'" type="password"
        :placeholder="field.placeholder">
    </ElInput>
    <!-- select  -->
    <div v-if="field.type == 'select'" class="flex flex-row items-center w-full">
        <ElSelect class="w-full" @change="handleUpdate" v-model="value" :placeholder="field.placeholder">
            <ElOption v-for="item in options" :key="item.value" :label="item.label" :value="item.value"></ElOption>
        </ElSelect>
        <div class="mr-2"></div>
        <!-- refresh  -->
        <ElButton @click="getOptions" type="text" link>
            <Icon icon="el:refresh" class=""></Icon>
        </ElButton>
    </div>
    <!-- switch -->
    <ElSwitch @change="handleUpdate" v-model="value" v-if="field.type == 'switch'"
        :active-text="field.checkedChildren"></ElSwitch>
    <!-- checkbox only one -->
    <ElCheckbox @change="handleUpdate" v-model="value" v-if="field.type == 'checkbox' && !field.multiple"
        :label="field.placeholder">
    </ElCheckbox>

    <!-- input  -->
    <ElInput v-model="value" @change="handleUpdate" :type="field.epInputType || 'text'" v-if="!field.type || field.type == 'input'"
        :placeholder="field.placeholder">
        <!-- suffix  -->
        <template v-if="field.suffix" #suffix>
            <div>
                {{ field.suffix }}
            </div>
        </template>
        <!-- prefix -->
        <template v-if="field.prefix" #prefix>
            <div>
                {{ field.prefix }}
            </div>
        </template>
    </ElInput>
</template>
<script>
import { request } from '@/api/request'
import { ElCheckbox } from 'element-plus';
export default {
    components: { ElCheckbox },
    props: {
        field: {
            type: Object,
            default: () => ({})
        },
        modelValue: {
            type: [String, Number, Array, Object],
            default: () => ''
        }
    },
    data() {
        return {
            options: [],
            value: ""
        };
    },
    watch: {
        modelValue: {
            handler(val) {
                if (this.field.type == 'switch') return this.value = !!val
                this.value = val
            },
            immediate: true
        },
        value: {
            handler(val) {
                this.$emit('update:modelValue', val)
            }
        }
    },
    computed: {},
    methods: {
        handleUpdate(val) {
            this.$emit('update:modelValue', val)
        },
        getOptions() {
            if (this.field.remoteDataApi) request.get(this.field.remoteDataApi).then(res => {
                this.options = (res.data?.data?.list || []).map(v => {
                    let label_name = this.field.props?.label || 'name'
                    let backend_label_names = ['name', 'title','label','username']
                    let value_name = this.field.props?.value || 'id'
                    let backend_value_names = ['id', 'value']

                    let label = ""
                    let value = ""

                    if(v[label_name]){
                        label = v[label_name]
                    }

                    if(v[value_name]){
                        value = v[value_name]
                    }

                    for (let i = 0; i < backend_label_names.length; i++) {
                        if (v[backend_label_names[i]]) {
                            label = v[backend_label_names[i]]
                            break
                        }
                    }

                    for (let i = 0; i < backend_value_names.length; i++) {
                        if (v[backend_value_names[i]]) {
                            value = v[backend_value_names[i]]
                            break
                        }
                    }
                    
                    return {
                        label,
                        value
                    }
                })
            })
        }
    },
    created() { },
    mounted() {
        this.getOptions()
    }
};
</script>
<style lang="scss" scoped></style>