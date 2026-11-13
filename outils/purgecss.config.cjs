module.exports = {
  content: [
    'resources/views/**/*.php',
    'Modules/**/*.php',
    'app/**/*.php',
    'public/assets/js/**/*.js',
    'public/assets/ganho/js/**/*.js',
    'public/assets/plugins/**/*.js',
  ],
  css: ['public/assets/css/style.css'],
  output: 'public/assets/css/style.purged.css',
  keyframes: true, fontFace: true, variables: false,
  defaultExtractor: c => c.match(/[A-Za-z0-9_:\/\-\.%]+/g) || [],
  safelist: {
    standard: ['html','body',/^modal/,/^show/,/^fade/,/^collaps/,/^active/,/^open/,/^dropdown/,/^select2/,/^dt-/,/^dataTable/,/^dataTables/,/^toast/,/^swal/,/^note-/,/^dark/,/^sidebar/,/^sidenav/,/^app/,/^ps/,/^tooltip/,/^popover/,/^bs-/,/^was-/,/^is-/,/^valid/,/^invalid/,/^disabled/,/^selected/,/^focus/,/^hover/,/^sorting/,/^paginate/,/^page-/,/^btn/,/^fixed/,/^sticky/,/^hidden/,/^visible/,/^slide/,/^menu/,/^side-/,/^header/,/^rtl/,/^ltr/,/^offcanvas/,/^tab/,/^nav/,/^alert/,/^badge/,/^form-/,/^input-/,/^custom-/,/^dz-/,/^dropzone/,/^daterange/,/^flatpickr/,/^ui-/,/^bootstrap-/,/^skin/,/^light/,/^transparent/,/^boxed/,/^layout/,/^scrollable/,/^mm-/,/^has-/,/^gn-/,/^gcx-/],
    deep: [/modal/,/dropdown/,/select2/,/dataTable/,/note-/,/dark-mode/,/toast/,/swal/],
    greedy: [/^dark-mode/,/^select2/,/^note-/,/data-/]
  }
};
