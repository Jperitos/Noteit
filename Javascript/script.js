const modal = document.getElementById('addNoteModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const form = document.getElementById('noteForm');

    openBtn.onclick = () => {
      modal.style.display = 'flex';
    };

    closeBtn.onclick = cancelBtn.onclick = () => {
      modal.style.display = 'none';
      form.reset();
    };



    // search
    document.addEventListener("DOMContentLoaded", function () {
      const searchInput = document.getElementById("searchInput");
      const notesContainer = document.getElementById("notesContainer");
  
      searchInput.addEventListener("input", function () {
          const query = searchInput.value.toLowerCase();
  
          const notes = notesContainer.getElementsByClassName("box");
  

          Array.from(notes).forEach(function (note) {
              const title = note.getAttribute("data-title").toLowerCase();
              const content = note.getAttribute("data-content").toLowerCase();
  
              if (title.includes(query) || content.includes(query)) {
                  note.style.display = "";
              } else {
                  note.style.display = "none";
              }
          });
      });
  });
  
  