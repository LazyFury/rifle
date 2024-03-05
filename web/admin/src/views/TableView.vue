<template>
    <div>
        <ElCard shadow="never">
            <!-- {{ $route.meta }} -->
            <div class="flex flex-row">
                <div>
                    <h1 class="mb-0 mt-2">{{ meta.title }}</h1>
                    <p class="text-gray">{{ meta.description }}</p>
                </div>
                <div class="flex-grow">
                    <div class="flex flex-row-reverse">

                    </div>
                </div>
            </div>
            <ElDivider class="!mb-4 !mt-2"></ElDivider>
            <div v-if="searchFormFields && searchFormFields.length > 0">
                <ElForm :inline="true" :model="searchForm" @submit.prevent.native="e=>{}" class="mb-2">
                    <ElFormItem v-for="field in searchFormFields" :key="field.name" :label="field.label" :prop="field.name"
                        :class="[]" :style="{ 'min-width': field.width || '100px' }">
                        <FormItem :field="field" v-model="searchForm[field.name]"></FormItem>
                    </ElFormItem>
                    <ElFormItem>
                        <ElButton type="primary" @click="submitSearch">
                            <Icon icon="heroicons-solid:magnifying-glass" class="mt-0px mr-4px"></Icon>
                            <span>{{ $t("search") }}</span>
                        </ElButton>
                        <!-- reset  -->
                        <ElButton type="default" @click="resetSearchForm">
                            <Icon icon="la:trash-restore-alt" class="mt-0px mr-4px"></Icon>
                            <span>{{ $t("reset") }}</span>
                        </ElButton>
                    </ElFormItem>
                </ElForm>
            </div>


            <!-- betch actions  -->
            <div class="mb-4">
                <ElButton :disabled="!canAdd" type="primary" @click="add">
                    <Icon icon="ant-design:plus-outlined"></Icon>
                    <span>添加</span>
                </ElButton>
                <ElButton :loading="loading" type="default" @click="load">
                    <Icon v-if="!loading" icon="ant-design:reload-outlined"></Icon>
                    <span>{{ $t("refresh") }}</span>
                </ElButton>

                <!-- divider vertical  -->
                <ElDivider direction="vertical" class="mx-4"></ElDivider>

                <ElButton :type="action.btnType" @click="batchAction(action.action)"
                    v-for="action in meta.table?.batchActions || []" :key="action.name">
                    {{ action.label }}
                </ElButton>
                <ElButton type="danger" @click="handleBatchDelete()">
                    <span>批量删除</span>
                </ElButton>
                <!-- 导出 -->
                <ElButton type="default" @click="exportData">
                    <Icon icon="ant-design:export-outlined"></Icon>
                    <span>导出</span>
                </ElButton>
            </div>
            <div class=" overflow-x-auto" style="width:calc(100vw - 300px)">
                <ElTable ref="tableRef" size="default" v-loading="loading" :data="tableData" :border="true" stripe
                    :tree-props="{ hasChildren: 'hasChildren', children: 'children' }" row-key="id"
                    @sort-change="handleSortChange">
                    <!-- selection  -->
                    <ElTableColumn type="selection" width="55"></ElTableColumn>

                    <!-- id  -->
                    <ElTableColumn label="ID" width="80" prop="id"></ElTableColumn>

                    <ElTableColumn v-for="column in columns" :key="column.key"
                        :sortable="column.sortable ? 'custom' : false" :label="column.title" :width="column.width">
                        <template #default="{ row }" v-if="!column.slot">
                            <div :class="[column.className]" v-if="column.type == 'render'">
                                {{ column.render(row) }}
                            </div>
                            <!-- switch  -->
                            <ElSwitch v-if="column.type == 'switch'" v-model="row[column.key]" inactive-color="#ff4949"
                                active-text="" inactive-text="" disabled></ElSwitch>
                            <!-- checkbox  -->
                            <ElCheckbox v-if="column.type == 'checkbox'" v-model="row[column.key]" disabled></ElCheckbox>

                            <!-- select  -->
                            <ElSelect v-if="column.type == 'select'" v-model="row[column.key]"
                                :placeholder="column.placeholder" clearable>
                                <ElOption v-for="option in column.options" :key="option.value" :label="option.label"
                                    :value="option.value"></ElOption>
                            </ElSelect>
                            <!-- icon  -->
                            <Icon v-if="column.type == 'icon'" :icon="row[column.key]" :class="[column.className]"></Icon>
                            <!-- image  -->
                            <ElImage v-if="column.type == 'image'" :src="$img(row[column.key])" fit="cover"
                                :preview-teleported="true"
                                :preview-src-list="row[column.key] ? [$img(row[column.key])] : []"
                                style="width: 50px; height: 50px;"></ElImage>
                            <ElTag v-if="column.type == 'tag'" :type="column.epType || 'success'">{{ row[column.key] }}
                            </ElTag>

                            <!-- link  -->
                            <ElLink type="primary" v-if="column.type == 'link'" :underline="false"
                                :href="makeUrl(row, column, column.key)" :target="column.url_target || '_self'">
                                {{ column.prefix }}{{ row[column.key] }}{{ column.suffix }}
                            </ElLink>
                        </template>
                        <template v-if="column.slot" #default="{ row }">
                            <slot :name="column.slot" :row="row"></slot>
                        </template>
                    </ElTableColumn>

                    <!-- actions  -->
                    <ElTableColumn fixed="right" v-if="actions?.length" label="操作" min-width="150px">
                        <template #default="{ row }">
                            <ElButton v-for="action in actions" link :key="action.key" :type="action.type || 'primary'"
                                @click="action.handler(row)">
                                {{ action.title }}
                            </ElButton>
                        </template>
                    </ElTableColumn>
                </ElTable>
            </div>
            <!-- pagination  -->
            <div class="flex mt-2">
                <ElPagination small layout="total,sizes, prev, pager, next, jumper" background :hide-on-single-page="false"
                    v-model:current-page="pagination.currentPage" v-model:page-size="pagination.pageSize"
                    :page-sizes="[5, 10, 20, 50, 100]" :total="pagination.total" @current-change="handleCurrentPageChange"
                    @size-change="handlePageSizeChange"></ElPagination>
            </div>
        </ElCard>

        <slot name="addModal">
            <ElDialog v-if="canAdd" title="提示" v-model="editModal" class="!md:w-640px !w-full !lg:w-960px">
                <template #header>
                    <div></div>
                </template>
                <slot name="addForm">
                    <Form ref="formRef" :title="meta.title" :defaultForm="addFormDefault" :fields="addForm"
                        @submit="handleAddSubmit">
                    </Form>
                </slot>
            </ElDialog>
        </slot>
    </div>
</template>
<script>
import { ElPagination } from 'element-plus';
import { request } from '@/api/request';
import Form from '@/views/components/Form.vue'
import FormItem from './components/FormItem.vue';

export default {
    components: { ElPagination, Form,FormItem },
    props: {},
    data() {
        return {
            meta: this.$route.meta,
            searchForm: {},
            pagination: {
                currentPage: 1,
                pageSize: this.$route.meta?.table?.pageSize || 10,
                total: 1000
            },
            tableData: [],
            loading: false,
            editModal: false
        };
    },
    watch: {},
    computed: {
        api() {
            return this.meta.api || this.meta.api_url
        },
        searchFormFields() {
            return this.meta.searchForm?.fields || this.meta.search_form_fields || []
        },
        addForm() {
            return this.meta.addForm || this.meta.add_form_fields || []
        },
        canAdd() {
            return this.addForm && this.addForm.length > 0
        },
        addFormDefault() {
            let obj = {}
            this.addForm.forEach(arr => {
                arr.forEach(field => {
                    obj[field.name] = field.defaultValue
                    if (typeof field.defaultValue == null || field.defaultValue == undefined) {
                        obj[field.name] = ""
                    }
                })
            })
            return obj
        },
        columns() {
            let columns = this.meta.table?.columns || this.meta.columns || []
            if (typeof columns === 'string') {
                columns = JSON.parse(columns)
            }
            if (typeof columns === 'string') {
                return
            }
            console.log(columns)
            columns = columns?.map(v => ({
                ...v,
                dataIndex: v.dataIndex ? v.dataIndex : 999
            })).sort((a, b) => a.dataIndex - b.dataIndex) || []
            return (columns || []).map(column => {
                return {
                    ...column,
                    type: column.type || 'render',
                    render: (row) => {
                        if (column.slot) return ""
                        if (column.type === '' || column.type === 'render') {
                            let { valueType: type, key, mapping_key, data = [], def, formatStr = "", prefix = "", suffix = "" } = column || {}
                            let formatConfig = column.formatter
                            if (type === 'mapping') {
                                console.log(data)
                                console.log(row[column.key])
                                return data.find(item => item[key] == row[column.key])?.[mapping_key] || def || ""
                            }

                            if (type === 'date') {
                                return row[column.key] ? this.$dayjs(row[column.key]).format('YYYY-MM-DD HH:mm:ss') : ''
                            }

                            if (type === 'datetime') {
                                return row[column.key] ? this.$dayjs(row[column.key]).format('YYYY-MM-DD HH:mm:ss') : ''
                            }
                            // number 
                            if (type === 'number') {
                                let result = row[column.key] ? this.$numeral(row[column.key]).format(formatStr || '0,0.00') : ''
                                return `${prefix}${result}${suffix}`
                            }

                            // bool 
                            if (type === 'boolean') {
                                return row[column.key] ? (formatConfig.trueText || '是') : (formatConfig.falseText || '否')
                            }
                        }
                        return row[column.key]
                    }
                }
            })
        },
        actions() {
            return (this.meta.table?.actions || [
                {
                    key: 'edit',
                    title: '编辑',
                    type: 'primary'
                },
                {
                    key: 'delete',
                    title: '删除',
                    type: 'danger'
                }
            ]).map(action => {
                return {
                    ...action,
                    handler: (row) => {
                        console.log(row)
                        if (action.key === 'delete') {
                            this.handleBatchDelete([row.id])
                        }
                        if (action.key === 'edit') {
                            this.editModal = true
                            this.$nextTick(() => {
                                this.$refs.formRef?.edit(row)
                                this.$emit("edit", row)
                            })
                        }
                    }
                }
            })
        }
    },
    methods: {
        add() {
            this.editModal = true
            this.$nextTick(() => {
                this.$refs.formRef?.add({})
                this.$emit("add")
            })
        },
        submitSearch() {
            console.log(this.searchForm)
            this.load()
        },
        resetSearchForm() {
            this.searchForm = {}
            this.load()

            // reset table sort
            this.$refs.tableRef.clearSort()
        },
        handlePageSizeChange(val) {
            this.pagination.pageSize = val
            this.load()
        },
        handleCurrentPageChange(val) {
            this.pagination.currentPage = val
            this.load()
        },
        load() {
            this.loading = true
            request({
                url: this.api,
                method: 'get',
                params: {
                    page: this.pagination.currentPage,
                    size: this.pagination.pageSize,
                    ...this.searchForm
                }
            }).then(res => {
                let data = res.data.data || {}
                this.tableData = data?.list || []
                let { size, page, total } = data?.pageable
                this.pagination = {
                    pageSize: size,
                    currentPage: page,
                    total
                }

            }).finally(() => {
                setTimeout(() => {
                    this.loading = false
                }, 200)
            })
        },
        handleSortChange({ column, order }) {
            if (!order) return
            let { no } = column
            let col = this.meta.table.columns[no - 1]
            this.searchForm.order_by = col.key + (order === 'ascending' ? '_asc' : '_desc')
            console.log(col)
            this.load()
        },
        getTableSelection() {
            return this.$refs.tableRef?.getSelectionRows() || []
        },
        getTableSelectionIds() {
            return this.getTableSelection().map(item => item.id)
        },
        batchAction(key) {
            let actionMap = {
                delete: () => this.handleBatchDelete()
            }
            actionMap[key]?.()
        },
        handleBatchDelete(ids = null) {
            if (!ids) ids = this.getTableSelectionIds()
            if (!ids.length) return
            this.$confirm('确定删除选中的数据吗？', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                console.log(ids)
                request.delete(this.api + ".delete", {
                    data: {
                        ids
                    },
                }).then(res => {
                    if (res.data.code == 200) {
                        this.$message.success("删除成功")
                        this.load()
                    }
                })
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '已取消删除'
                });
            });
        },
        exportData() {
            this.$confirm('确定导出当前数据吗？', '提示', {
                confirmButtonText: this.$t("export"),
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                this.exportDataApi()
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '已取消导出'
                });
            });
        },
        exportDataApi() {
            console.log('export')
            request({
                url: this.api + '.export',
                method: 'get',
                responseType: 'blob',
                params: {
                    ...this.searchForm
                },
                binary: true
            }).then(res => {
                let blob = new Blob([res.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' })
                let url = window.URL.createObjectURL(blob)
                let link = document.createElement('a')
                link.style.display = 'none'
                link.href = url
                let filename = `${this.meta.title}-导出-${this.$dayjs().format('YYYYMMDDHHmmss')}`
                link.setAttribute('download', `${filename}.csv`)
                document.body.appendChild(link)
                link.click()
                document.body.removeChild(link)
                window.URL.revokeObjectURL(url)
            })
        },
        handleAddSubmit(form) {
            console.log(form)
            if (form.id) {
                request.put(this.api + ".update", form).then(res => {
                    if (res.data?.code == 200) {
                        this.$message.success("修改成功")
                        this.editModal = false
                        this.load()
                    }
                })
                return
            }

            request.post(this.api + ".create", form).then(res => {
                if (res.data?.code == 200) {
                    this.$message.success("添加成功")
                    this.editModal = false
                    this.load()
                }
            })
        },
        makeUrl(row, column, key) {
            let url = column.url_prefix || ""
            return url + row[key]
        },
        handleQueryToSearchForm(query) {
            for (let key in query) {
                if (this.searchFormFields.find(v => v.name === key)) {
                    this.searchForm[key] = query[key]
                }
            }
            this.load()
        }
    },
    created() { },
    mounted() {
        this.handleQueryToSearchForm(this.$route.query)
        this.load()
    }
};
</script>
<style lang="scss" scoped></style>