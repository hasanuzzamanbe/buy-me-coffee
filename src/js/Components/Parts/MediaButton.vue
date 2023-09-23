<template>
  <button type="button" class='rounded-sm bg-primary-500 py-2 px-4 border-0 shadow text-[14px] font-medium cursor-pointer'
          @click="openMediaFrame"><UploadFilled />Update Image
  </button>
</template>

<script setup>
import {onMounted} from "vue";
import {UploadFilled} from "@element-plus/icons-vue";

let mediaFrame = null;

const emit = defineEmits(['onMediaSelected'])

const openMediaFrame = () => {
  if (mediaFrame == null) {
    return
  }
  mediaFrame.open();
}

onMounted(() => {

  if (!typeof window.wp.media === 'function') {
    return
  }

  mediaFrame = window.wp.media({
    title: 'Select or Upload Media Of Your Chosen Persuasion',
    button: {
      text: 'Use this media'
    },
    multiple: true  // Set to true to allow multiple files to be selected
  });
  listenForMediaChange();

})

const listenForMediaChange = () => {
  mediaFrame.on('select', function () {
    const attachments = mediaFrame.state().get('selection').toJSON()
    emit('onMediaSelected', attachments)
  })
}
</script>