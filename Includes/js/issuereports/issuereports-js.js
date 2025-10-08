$(document).ready(function () {
    $('#dataTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        info: false,
        ordering: false,
    });
});

function showReportsDetails(idReport, reportedBy, reportedId, reporterName, reportedByName, reportType, reportAction, suspendedUntil, reportDetails, reason, status) {
    const screenWidth = window.innerWidth;
    let imageWidth;
    let imageHeight;
    let swalWidth;
    var showConfirmButtonStatus = true;
    var margin = "";
    var reasonDetails = "";
    var disabledValue = "";
    var suspendedSelectedValue = "";
    var suspendedDisabledSelectedValue = "";
    var banDisabledSelectedValue = "";
    var banSelectedValue = "";
    var sanctionDateValue = "";
    var sanctionDateDisplay = "";
    var display = "";
    // const showDenyButtonStatus = true;

    swalTitle = status == 1 ? "Shop" : "User";

    if(reportAction !== 0 && suspendedUntil !== "N/A") {
        showConfirmButtonStatus = false;
        margin = "mb-3";
        reasonDetails = reason;
        disabledValue = "disabled";

        swalTitle = status == 1 ? "Shop Details" : "Details"; 
        
        if(reportAction == 1) {
            suspendedSelectedValue = "selected";
            suspendedDisabledSelectedValue = "disabled";
            sanctionDateValue = suspendedUntil;
            
        } else if(reportAction == 2) {
            banSelectedValue = "selected";
            banDisabledSelectedValue = "disabled";
            sanctionDateDisplay = "style='display: none;'";
            margin = "";
        }
    } else {
        console.log('mali');
    }

    if (screenWidth >= 1600) {
        imageWidth = 450;
        imageHeight = 575;
        row = 10;
        swalWidth = '55vw';
    } else if (screenWidth >= 1200) {
        imageWidth = 350;
        imageHeight = 350;
        row = 6;
        swalWidth = '60vw';
    } else {
        imageWidth = 300;
        imageHeight = 300;
        row = 4;
        swalWidth = '90vw';
        display = sanctionDateDisplay;
    }

    Swal.fire({
        title: "",
        html: `
            <div class='row mb-2 mb-sm-5 mb-md-5'>
                <div class="text-start">
                    <h1 style="color: #1A3D63;">Report ${swalTitle}</h1>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-1"></div>
                <div class="col-12 col-sm-3 col-md-3">
                    <p class="text-start fs-4 fw-bolder">User Details</p>
                </div>
                <div class="col-12 mb-2 mb-sm-0 mb-md-0 col-sm-3 col-md-3">
                    <input type="text" class="form-control" value="${reporterName}" disabled>
                </div>
                <div class="col-12 col-sm-3 col-md-3">
                    <input type="text" class="form-control" value="${reportedByName}" disabled>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-1"></div>
                <div class="col-12 col-sm-3 col-md-3">
                    <p class="text-start fs-4 fw-bolder">Report Info</p>
                </div>
                <div class="col-12 col-sm-7 col-md-7">
                    <input type="text" class="form-control" value="${reportType}" disabled>
                </div>
            </div>
            <div class="row ${margin}">
                <div class="col-1"></div>
                <div class="col-12 col-sm-3 col-md-3">
                    <p class="text-start fs-4 fw-bolder">Sanction Type</p>
                </div>
                <div class="col-12 mb-2 mb-sm-0 mb-md-0 col-sm-3 col-md-3">
                    <select class="form-select" id="sanctionSelect" ${suspendedDisabledSelectedValue} ${banDisabledSelectedValue}> 
                        <option disabled selected value="">Select Sanction</option>
                        <option value="1" ${suspendedSelectedValue}>Suspended</option>
                        <option value="2" ${banSelectedValue}>Ban</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4 col-md-4">
                    <input type="datetime-local" class="form-control" id="sanctionDate" value="${sanctionDateValue}" ${display} disabled>
                </div>
            </div>
            <div class="row ${margin}">
                <div class="col-1"></div>
                <div class="col-12 col-md-3 col-sm-3">
                    <p class="text-start fs-4 fw-bolder">Reason</p>
                </div>
                <div class="col-12 col-sm-7 col-md-7">
                    <textarea class="form-control" rows="${row}" id="reasonInput" ${disabledValue}>${reasonDetails}</textarea>
                </div>
            </div>
        `,
        width: swalWidth,
        allowOutsideClick: false,
        showCloseButton: true,
        showDenyButton: false,
        showConfirmButton: showConfirmButtonStatus,
        denyButtonText: "Reject",
        confirmButtonText: "Ban " + swalTitle,
        denyButtonColor: "#dc3545",
        confirmButtonColor: "#dc3545",

        didOpen: () => {
            const sanctionSelect = Swal.getPopup().querySelector('#sanctionSelect');
            const datetimeInput = Swal.getPopup().querySelector('#sanctionDate');

            const now = new Date();
            const formatted = now.toISOString().slice(0,16);
            datetimeInput.min = formatted;

            sanctionSelect.addEventListener('change', function () {
                if (this.value === "2") {
                    // Ban selected
                    datetimeInput.disabled = true;
                    datetimeInput.value = "";
                } else if (this.value === "1") {
                    // Suspended selected
                    datetimeInput.disabled = false;
                }
            });
        },

        preConfirm: () => {
            const sanction = Swal.getPopup().querySelector('#sanctionSelect').value;
            const date = Swal.getPopup().querySelector('#sanctionDate').value;
            const reason = Swal.getPopup().querySelector('#reasonInput').value.trim();

            if (!sanction) {
                Swal.showValidationMessage(`Please select a sanction type`);
                return false;
            }
            if (sanction === "1" && !date) { 
                Swal.showValidationMessage(`Please provide a suspension date/time`);
                return false;
            }
            if (!reason) {
                Swal.showValidationMessage(`Please enter a reason`);
                return false;
            }

            return { sanction, date, reason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { sanction, date, reason } = result.value;
            $.ajax({
                url : "../../Includes/php/issuereports/issuereportsAjax.php",
                type : "POST",
                dataType : "json",
                data : {
                    sanctionLevel : sanction,
                    reason : reason,
                    suspensionTime : date,
                    idReport : idReport,
                    reportedBy : reportedBy,
                    reportedId : reportedId,
                    status : status,
                },
                success : function(data) {
                    if(data.status === 'success') {
                        Swal.fire({
                            title: data.alertTitle + " Report",
                            text: "Successful, " + data.alertTitle + " is " + data.alertText + ".", 
                            icon: "success",
                            showConfirmButton: false,
                            timer : 1500,
                            scrollbarPadding: false,
                        }).then((result) => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: "There is an error in query, please try again!",
                            icon: "error",
                            showConfirmButton: false,
                            timer: 1500,
                            scrollbarPadding: false,
                        });
                    }
                }
            })
        }
        // else if (result.isDenied) {
        //     alert('deny');
        // }
    });
}