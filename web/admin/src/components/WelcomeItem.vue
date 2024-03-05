<template>
  <ElCard shadow="never" :border="false" class="" style="box-sizing: border-box;">
    <div class="item">
      <i>
        <slot name="icon"></slot>
      </i>
      <div class="details">
        <h3 class="my-1 mt-2 font-normal">
          <slot name="heading"></slot>
        </h3>
        <div class="text-gray">
          <slot></slot>
        </div>
      </div>
    </div>
  </ElCard>
</template>

<style scoped></style>
