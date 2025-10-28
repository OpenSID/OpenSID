// Global variables with better naming and initialization
let isDataPertanyaanExist = false;
let countCheckedCheckbox = 0;

/**
 * Assign values from question rows to answer rows
 * Improved with better variable naming and error handling
 */
function assignValue() {
  let isSettingApplicable = true;
  
  $(".row-pertanyaan").each(function (index, element) {
    const $currentRow = $(element);
    const rowId = $currentRow.find(".input-id").val();
    const $answerRow = $("#row-jawaban-" + rowId);
    
    if (!$answerRow.length) {
      console.warn(`Answer row not found for ID: ${rowId}`);
      return true; // Continue to next iteration
    }

    try {
      // Transfer checkbox values
      $answerRow.find(".is-selected").val($currentRow.find(".input-is-selected").prop("checked"));
      $answerRow.find(".is-nik-kk").val($currentRow.find(".input-is-nik-kk").prop("checked"));
      
      // Transfer input values
      $answerRow.find(".tipe").val($currentRow.find(".input-tipe").val());
      $answerRow.find(".kategori").val($currentRow.find(".input-kategori").val());
      $answerRow.find(".bobot").val($currentRow.find(".input-bobot").val());

      // Show/hide answer form based on conditions
      const shouldShow = shouldShowAnswerForm($currentRow);
      if (shouldShow) {
        $answerRow.show();
        isSettingApplicable = false;
      } else {
        $answerRow.hide();
      }
    } catch (error) {
      console.error(`Error processing row ${rowId}:`, error);
    }
  });

  // Toggle empty state caption
  $("#caption-jawaban").toggle(isSettingApplicable);
}

/**
 * Check if answer form should be displayed for given question row
 * @param {jQuery} $questionRow - The question row element
 * @returns {boolean} Whether to show the answer form
 */
function shouldShowAnswerForm($questionRow) {
  const questionType = $questionRow.find(".input-tipe").val();
  const isSelected = $questionRow.find(".input-is-selected").prop("checked");
  const isNikKk = $questionRow.find(".input-is-nik-kk").prop("checked");
  
  return questionType === "1" && isSelected && !isNikKk;
}

/**
 * Configure NIK/KK specific settings for a question row
 * @param {jQuery} objRow - The question row to configure
 * @param {boolean} setEnable - Whether to enable NIK/KK mode (default: true)
 */
function setAsNikKK(objRow, setEnable = true) {
  if (!objRow || !objRow.length) {
    console.warn("Invalid row object passed to setAsNikKK");
    return;
  }
  
  // Always set bobot to 0 for NIK/KK
  objRow.find(".input-bobot").val("0");
  
  if (setEnable) {
    // Enable NIK/KK mode
    objRow.find(".input-is-selected")
      .prop("disabled", true)
      .prop("title", "NIK/No. KK harus disimpan");
    
    objRow.find(".input-tipe").prop("disabled", true);
    objRow.find(".input-kategori")
      .val("NIK/No. KK")
      .prop("disabled", true);
    objRow.find(".input-bobot").prop("disabled", true);
  } else {
    // Disable NIK/KK mode
    objRow.find(".input-is-selected")
      .prop("disabled", false)
      .prop("title", "");
    
    objRow.find(".input-tipe").prop("disabled", false);
    objRow.find(".input-kategori")
      .val("")
      .prop("disabled", false);
    objRow.find(".input-bobot").prop("disabled", false);
  }
}

/**
 * Set question selection state and update form controls
 * @param {jQuery} objRow - The question row to modify
 * @param {boolean} setSelected - Whether to select the question (default: true)
 */
function setSelectedQuestion(objRow, setSelected = true) {
  if (!objRow || !objRow.length) {
    console.warn("Invalid row object passed to setSelectedQuestion");
    return;
  }
  
  // Update selection state
  objRow.find(".input-is-selected")
    .prop("checked", setSelected)
    .data("waschecked", setSelected);
  
  // Reset NIK/KK and form values
  setAsNikKK(objRow, false);
  objRow.find(".input-kategori").val("");
  objRow.find(".input-bobot").val("0");

  // Enable/disable form controls based on selection
  const formControls = [".input-is-nik-kk", ".input-tipe", ".input-kategori", ".input-bobot"];
  formControls.forEach(selector => {
    objRow.find(selector).prop("disabled", !setSelected);
  });
}

/**
 * Update the state of select-all checkbox based on individual selections
 * Improved with better logic and performance
 */
function checkAllCheckbox() {
  countCheckedCheckbox = 0;
  const $allCheckboxes = $(".input-is-selected");
  const totalCheckboxes = $allCheckboxes.length;
  
  // Count checked checkboxes
  $allCheckboxes.each(function() {
    if ($(this).prop("checked")) {
      countCheckedCheckbox++;
    }
  });

  const $selectAllBtn = $("#select-all-question");

  if (countCheckedCheckbox === totalCheckboxes) {
    // All selected
    $selectAllBtn
      .prop("checked", true)
      .prop("indeterminate", false)
      .data("waschecked", true);
  } else if (countCheckedCheckbox === 0) {
    // None selected
    $selectAllBtn
      .prop("checked", false)
      .prop("indeterminate", false)
      .data("waschecked", false);
  } else {
    // Some selected (indeterminate state)
    $selectAllBtn
      .prop("indeterminate", true)
      .data("waschecked", false);
  }
}

/**
 * Validate question modal form inputs
 * @returns {string} Error message or empty string if valid
 */
function validasiModalPertanyaan() {
  // Check if NIK/KK is selected
  const nikKkValue = $("#id-row-nik-kk").val();
  if (!nikKkValue || nikKkValue.trim() === "") {
    return "Kolom NIK/No. KK Belum Ditentukan";
  }

  // Validate selected questions
  let validationError = "";
  
  $(".row-pertanyaan").each(function (index, element) {
    const $currentRow = $(element);
    
    if ($currentRow.find(".input-is-selected").prop("checked")) {
      const questionText = $currentRow.find(".input-pertanyaan").html() || `Pertanyaan ${index + 1}`;
      const kategoriValue = $currentRow.find(".input-kategori").val();
      const tipeValue = $currentRow.find(".input-tipe").val();

      // Check if category is filled
      if (!kategoriValue || kategoriValue === "undefined" || kategoriValue.trim() === "") {
        validationError = `Kategori untuk Pertanyaan "${questionText}" belum diisi`;
        return false; // Break out of each loop
      }

      // Check if question type is selected
      if (!tipeValue || tipeValue === "0") {
        validationError = `Tipe Pertanyaan untuk Pertanyaan "${questionText}" belum diisi`;
        return false; // Break out of each loop
      }
    }
  });

  return validationError;
}

/**
 * Validate answer modal form inputs
 * @returns {string} Error message or empty string if valid
 */
function validasiModalJawaban() {
  // Define validation rules
  const validationRules = [
    {
      selector: "#nama_form",
      errorMessage: "Nama Form Analisis Belum Ditentukan"
    },
    {
      selector: "#tahun_pendataan", 
      errorMessage: "Tahun Pendataan Belum Ditentukan"
    },
    {
      selector: "#subjek_analisis",
      errorMessage: "Subjek Analisis Belum Ditentukan",
      checkZero: true
    }
  ];

  // Check each validation rule
  for (const rule of validationRules) {
    const fieldValue = $(rule.selector).val();
    
    if (!fieldValue || fieldValue.trim() === "" || (rule.checkZero && fieldValue === "0")) {
      return rule.errorMessage;
    }
  }

  return "";
}

/**
 * Handle NIK/KK selection logic
 * @param {jQuery} $clickedElement - The clicked NIK/KK checkbox
 */
function handleNikKkSelection($clickedElement) {
  const $parentRow = $clickedElement.closest(".row-pertanyaan");
  const wasChecked = $clickedElement.data("waschecked") === true;
  
  if (wasChecked) {
    // Deselect current NIK/KK
    $clickedElement.prop("checked", false).data("waschecked", false);
    $("#id-row-nik-kk").val("");
    $("#gform-id-nik-kk").val("");
    setAsNikKK($parentRow, false);
  } else {
    // First, deselect all other NIK/KK options
    $(".input-is-nik-kk").each(function() {
      const $current = $(this);
      $current.prop("checked", false).data("waschecked", false);
      setAsNikKK($current.closest(".row-pertanyaan"), false);
    });

    // Then select the clicked one
    $clickedElement.prop("checked", true).data("waschecked", true);
    $("#id-row-nik-kk").val($parentRow.find(".input-id").val());
    $("#gform-id-nik-kk").val($parentRow.find(".input-item-id").val());
    setAsNikKK($parentRow, true);
  }
}

/**
 * Handle select all functionality
 * @param {jQuery} $selectAllElement - The select all checkbox element
 */
function handleSelectAllQuestions($selectAllElement) {
  const wasChecked = $selectAllElement.data("waschecked") === true;
  const nikKkId = $("#id-row-nik-kk").val();

  $(".row-pertanyaan").each(function() {
    const $currentRow = $(this);
    const rowId = $currentRow.find(".input-id").val();
    
    // Skip the NIK/KK row
    if (rowId !== nikKkId) {
      setSelectedQuestion($currentRow, !wasChecked);
    }
  });

  // Update select-all button state
  if (wasChecked) {
    $selectAllElement.data("waschecked", false);
    if (nikKkId && nikKkId.trim() !== "") {
      $selectAllElement.prop("indeterminate", true);
    }
  } else {
    $selectAllElement
      .prop("checked", true)
      .data("waschecked", true)
      .prop("indeterminate", false);
  }
}

// Document ready function with improved error handling and organization
$(document).ready(function () {
  try {
    // Initialize based on form mode
    const formMode = $("#mode-form").val();
    const errorCount = $("#jml_error").val();

    if (formMode === "5") {
      $("#modalPertanyaan").modal("show");
    } else if (errorCount !== "0") {
      $("#modalHasilImport").modal("show");
    }

    // Button event handlers with improved error handling
    $("#btn-next-pertanyaan").on("click", function() {
      const validationError = validasiModalPertanyaan();
      if (validationError) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: validationError,
            confirmButtonText: 'OK'
          });
        } else {
          alert(validationError); // Fallback if Swal not available
        }
      } else {
        assignValue();
        $("#modalPertanyaan").modal("hide");
        isDataPertanyaanExist = true;
      }
    });

    $("#btn-prev-jawaban").on("click", function() {
      $("#modalJawaban").modal("hide");
      isDataPertanyaanExist = true;
    });

    $("#btn-next-jawaban").on("click", function() {
      const validationError = validasiModalJawaban();
      if (validationError) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: validationError,
            confirmButtonText: 'OK'
          });
        } else {
          alert(validationError); // Fallback if Swal not available
        }
      } else {
        $("#form-jawaban").submit();
      }
    });

    // Modal event handlers
    $("#modalPertanyaan").on("hidden.bs.modal", function() {
      if (isDataPertanyaanExist) {
        $("#modalJawaban").modal("show");
        isDataPertanyaanExist = false;
      }
    });

    $("#modalJawaban").on("hidden.bs.modal", function() {
      if (isDataPertanyaanExist) {
        $("#modalPertanyaan").modal("show");
        isDataPertanyaanExist = false;
      }
    });

    // NIK/KK selection handler with event delegation for dynamic content
    $(document).on("click", ".input-is-nik-kk", function() {
      handleNikKkSelection($(this));
      checkAllCheckbox();
    });

    // Question selection handler with event delegation
    $(document).on("click", ".input-is-selected", function() {
      const $currentRow = $(this).closest(".row-pertanyaan");
      const wasChecked = $(this).data("waschecked") === true;
      
      setSelectedQuestion($currentRow, !wasChecked);
      checkAllCheckbox();
    });

    // Select all handler
    $("#select-all-question").on("click", function() {
      handleSelectAllQuestions($(this));
    });

  } catch (error) {
    console.error("Error initializing question form:", error);
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'error',
        title: 'Kesalahan Sistem',
        text: 'Terjadi kesalahan saat memuat form. Silakan refresh halaman.',
        confirmButtonText: 'OK'
      });
    } else {
      alert('Terjadi kesalahan saat memuat form. Silakan refresh halaman.'); // Fallback if Swal not available
    }
  }
});