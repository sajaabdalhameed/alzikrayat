/**
 * Client-side validation script for Alzikrayat forms.
 * This is the second validation layer (after native HTML5) and runs before
 * the form is ever submitted to the server-side validation layer.
 */
document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form");

    forms.forEach((form) => {
        form.addEventListener("submit", function (event) {
            let isValid = true;
            const fields = form.querySelectorAll("input[required], textarea[required]");

            fields.forEach((field) => {
                let fieldIsValid = true;

                if (!field.value.trim()) {
                    fieldIsValid = false;
                }

                const requiredPattern = field.getAttribute("pattern");
                if (fieldIsValid && requiredPattern) {
                    const regex = new RegExp("^(?:" + requiredPattern + ")$");
                    if (!regex.test(field.value.trim())) {
                        fieldIsValid = false;
                    }
                }

                const minLength = field.getAttribute("minlength");
                if (fieldIsValid && minLength && field.value.length < parseInt(minLength, 10)) {
                    fieldIsValid = false;
                }

                if (field.type === "email" && fieldIsValid) {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(field.value.trim())) {
                        fieldIsValid = false;
                    }
                }

                field.classList.toggle("is-invalid", !fieldIsValid);
                if (!fieldIsValid) {
                    isValid = false;
                }
            });

            if (!isValid) {
                event.preventDefault();
                alert("Please review the highlighted fields before submitting.");
            }
        });
    });
});

/**
 * Submits a new comment via AJAX (fetch) and appends it to the comments list
 * immediately, without a full page reload. Falls back to a normal form submit
 * automatically if JavaScript is unavailable, since the <form> keeps its
 * regular method/action attributes.
 */
document.addEventListener("DOMContentLoaded", function () {
    const commentForm = document.getElementById("commentForm");
    if (!commentForm) {
        return;
    }

    commentForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(commentForm);
        const commentsList = document.getElementById("commentsList");
        const textArea = commentForm.querySelector("textarea[name='comment']");

        fetch(commentForm.action, {
            method: "POST",
            headers: { "X-Requested-With": "XMLHttpRequest" },
            body: formData
        })
            .then((response) => response.json())
            .then((data) => {
                if (!data.success) {
                    alert(data.message || "Could not post the comment.");
                    return;
                }

                const emptyNotice = commentsList.querySelector(".text-muted");
                if (emptyNotice) {
                    emptyNotice.remove();
                }

                const commentBlock = document.createElement("div");
                commentBlock.className = "border-bottom py-2";
                const nameStrong = document.createElement("strong");
                nameStrong.textContent = data.comment.authorName + ":";
                commentBlock.appendChild(nameStrong);
                commentBlock.append(" " + data.comment.text);

                commentsList.appendChild(commentBlock);
                commentsList.scrollTop = commentsList.scrollHeight;
                textArea.value = "";
            })
            .catch(() => {
                alert("Network error while posting the comment.");
            });
    });
});
