<template>
  <div>
    <TableView ref="tableRef" @add="handleAdd" @edit="handleEdit">
      <template #addForm>
        <div>
          <Form ref="formRef" :title="meta.name" :defaultForm="{}" :fields="meta.add_form_fields"
            :progressFormData="handleProgressFormData" @submit="handleAddSubmit">

            <!-- columns  -->
            <template #columns="{ form, field }">
              <div class="overflow-x-auto w-full">

                <!-- table -->
                <!-- fields: title type key dataIndex width align fixed render scopedSlots -->
                <ElTable :data="form[field.name]">
                  <ElTableColumn v-for="column in table_columns_attrs" :label="column.label" :prop="column.key"
                    :width="column.width || 'auto'">
                    <template #default="{ row }">
                      <ElInput v-model="row[column.key]" placeholder="请输入" />
                    </template>
                  </ElTableColumn>
                  <!-- 操作 -->
                  <ElTableColumn label="操作" width="auto">
                    <template #default="{ row }">
                      <ElButton type="danger" link @click="handleDeleteColumn(form, field.name, row)">删除</ElButton>
                    </template>
                  </ElTableColumn>
                </ElTable>

                <!-- btn add  -->
                <div class="mt-2">
                  <ElButton type="primary" link @click="handleAddColumn(form, field.name)">添加表格列</ElButton>
                </div>
              </div>
            </template>

            <template #search_form_fields="{ form, field }">
              <div class="overflow-x-auto !w-full">
                <ElTable :data="form[field.name]">
                  <ElTableColumn v-for="column in search_form_fields_attrs" :label="column.label" :prop="column.key"
                    :width="column.width || 'auto'">
                    <template #default="{ row }">
                      <ElInput v-model="row[column.key]" placeholder="请输入" />
                    </template>
                  </ElTableColumn>

                  <ElTableColumn label="操作" width="auto">
                    <template #default="{ row }">
                      <ElButton type="danger" link @click="handleDeleteColumn(form, field.name, row)">删除</ElButton>
                    </template>
                  </ElTableColumn>
                </ElTable>
                <!-- btn add  -->
                <div class="mt-2">
                  <ElButton type="primary" link @click="handleAddSearchFields(form, field.name)">添加表格列</ElButton>
                </div>
              </div>
            </template>

            <template #add_form_fields="{ form, field }">
              <div class="overflow-x-auto">
                <!-- add table  -->
                <ElButton type="success" size="small" @click="handleAddAddFormTable(form, field.name)">添加Layout</ElButton>
                <div class="overflow-x-auto" v-for="(table, i) in form[field.name]">
                  <ElTable :data="table">
                    <ElTableColumn v-for="column in edit_form_fields_attrs" :label="column.label" :prop="column.key"
                      :width="column.width || 'auto'">
                      <template #default="{ row }">
                        <FormItem :field="handleField(column, row)" v-model="row[column.key]"></FormItem>
                      </template>
                    </ElTableColumn>
                    <ElTableColumn label="操作" width="auto">
                      <template #default="{ row }">
                        <ElButton type="danger" link @click="handleDeleteAddColumn(form, field.name, row, i)">删除</ElButton>
                      </template>
                    </ElTableColumn>
                  </ElTable>
                  <!-- btn add  -->
                  <div class="mt-2">
                    <ElButton type="primary" link @click="handleAddAddColumn(form, field.name, i)">添加表格列</ElButton>
                  </div>
                </div>
              </div>
            </template>
          </Form>
        </div>
      </template>
    </TableView>
  </div>
</template>
<script>
import { ElTable, ElTableColumn, ElInput } from 'element-plus';
import TableView from '../TableView.vue';
import Form from '../components/Form.vue';
import FormItem from '../components/FormItem.vue';
export default {
  components: {
    TableView, Form,
    ElTable,
    ElTableColumn,
    FormItem,
  },
  props: {},
  data() {
    return {
      meta: this.$route.meta,
      search_form_fields_attrs: [
        { key: 'name', label: 'name' },
        { key: 'label', label: 'label' },
        { key: 'type', label: 'type' },
        { key: 'key', label: 'key' },
        { key: 'placeholder', label: 'placeholder', width: '200px' },
      ],
      table_columns_attrs: [
        { key: 'title', label: 'title' },
        // label 
        { key: 'type', label: 'type' },
        { key: 'key', label: 'key' },
        { key: 'dataIndex', label: 'dataIndex' },
        { key: 'width', label: 'width' },
        // prefix ,suffix
        { key: "prefix", label: "prefix" },
        { key: "suffix", label: "suffix" },
        { key: "className", label: "className" },
        // valueType
        { key: "valueType", label: "valueType" },
        // url_prefix 
        { key: "url_prefix", label: "url_prefix" },
        // url_target
        { key: "url_target", label: "url_target" },
      ],
      edit_form_fields_attrs: [
        // label,name,type,placeholder,required,disabled,defaultValue,hidden,clearable,showPassword,remoteDataApi,prefix,suffix,epInputType
        { key: 'label', label: 'label' },
        { key: 'name', label: 'name' },
        { key: 'type', label: 'type' },
        { key: 'placeholder', label: 'placeholder' },
        // width 
        { key: 'width', label: 'width' },
        { key: 'required', label: 'required', type: 'switch' },
        { key: 'disabled', label: 'disabled', type: 'switch' },
        { key: 'defaultValue', label: 'defaultValue' },
        { key: 'hidden', label: 'hidden', type: 'switch' },
        { key: 'clearable', label: 'clearable' },
        { key: 'showPassword', label: 'showPassword' },
        { key: 'remoteDataApi', label: 'remoteDataApi' },
        { key: 'prefix', label: 'prefix' },
        { key: 'suffix', label: 'suffix' },
        { key: 'epInputType', label: 'epInputType' },
      ],
    };
  },
  watch: {},
  computed: {

  },
  methods: {
    handleField(column, row) {
      if (column.key == 'defaultValue') {
        return {
          ...column,
          type: row.type
        }
      }
      return {
        ...column,

      }
    },
    handleAddSubmit(data) {
      this.$refs.tableRef?.handleAddSubmit(data);
    },
    handleAdd() {
      this.$refs.formRef?.add();
    },
    handleEdit(row) {
      this.$refs.formRef?.edit(row);
    },
    handleAddColumn(form, key) {
      if (!form[key]) {
        form[key] = [];
      }
      form[key].push({
        title: '',
        type: '',
        key: '',
        dataIndex: '',
        width: 120,
      });
    },
    handleAddSearchFields(form, key) {
      if (!form[key]) {
        form[key] = [];
      }
      form[key].push({
        title: '',
        type: '',
        key: '',
        width: 120,
        placeholder: '',
      });
    },
    handleProgressFormData(data) {
      let res = {};
      for (let key in data) {
        if (['columns', 'search_form_fields'].includes(key)) {
          if (!data[key]) {
            res[key] = [];
            continue;
          }
        }
        res[key] = data[key];

      }
      return res;
    },
    handleAddAddFormTable(form, key) {
      if (!form[key]) {
        form[key] = [];
      }
      form[key].push([{
        label: '',
        name: '',
        type: '',
        placeholder: '',
        required: false,
        disabled: false,
        defaultValue: '',
        hidden: false,
        clearable: false,
        showPassword: false,
        remoteDataApi: '',
        prefix: '',
        suffix: '',
        epInputType: '',
      }]);
    },
    handleAddAddColumn(form, key, i) {
      if (!form[key][i]) {
        form[key][i] = [];
      }
      form[key][i].push({
        label: '',
        name: '',
        type: '',
        placeholder: '',
        required: false,
        disabled: false,
        defaultValue: '',
        hidden: false,
        clearable: false,
        showPassword: false,
        remoteDataApi: '',
        prefix: '',
        suffix: '',
        epInputType: '',
      });
    },
    handleDeleteAddColumn(form, key, row, i) {
      form[key][i] = form[key][i].filter(el => el != row);
    },
    handleDeleteColumn(form, key, row) {
      form[key] = form[key].filter(el => el != row);
    }
  },
  created() { },
  mounted() {

  }
};
</script>
<style lang="scss" scoped></style>