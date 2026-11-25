document.querySelectorAll(".quote-especial-2027-audio").forEach((player) => {
        const btn = player.querySelector(".quote-especial-2027-play");
        const audio = player.querySelector(".quote-especial-2027-src");
        const fill = player.querySelector(".quote-especial-2027-progress-fill");
        const bar = player.querySelector(".quote-especial-2027-progress-bar");
        const time = player.querySelector(".quote-especial-2027-time");
        if (!btn || !audio) return;

        const formatTime = (seconds) => {
            if (!Number.isFinite(seconds) || seconds < 0) return "0:00";
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return mins + ":" + String(secs).padStart(2, "0");
        };

        const updateProgress = () => {
            const duration = audio.duration;
            const current = audio.currentTime || 0;
            const percent = duration > 0 ? (current / duration) * 100 : 0;
            if (fill) fill.style.width = percent + "%";
            if (bar) bar.setAttribute("aria-valuenow", String(Math.round(percent)));
            if (time) time.textContent = formatTime(current) + " / " + formatTime(duration);
        };

        const setPlaying = (playing) => {
            btn.classList.toggle("is-playing", playing);
            btn.setAttribute("aria-label", playing ? "Pausar audio" : "Reproducir audio");
        };

        const pauseOthers = () => {
            document.querySelectorAll(".quote-especial-2027-audio").forEach((other) => {
                if (other === player) return;
                const otherAudio = other.querySelector(".quote-especial-2027-src");
                const otherBtn = other.querySelector(".quote-especial-2027-play");
                if (otherAudio) {
                    otherAudio.pause();
                    otherAudio.currentTime = 0;
                }
                if (otherBtn) {
                    otherBtn.classList.remove("is-playing");
                    otherBtn.setAttribute("aria-label", "Reproducir audio");
                }
            });
        };

        btn.addEventListener("click", () => {
            if (!audio.getAttribute("src")) return;

            if (audio.paused) {
                pauseOthers();
                audio.play();
                setPlaying(true);
            } else {
                audio.pause();
                setPlaying(false);
            }
        });

        if (bar) {
            bar.addEventListener("click", (event) => {
                if (!audio.duration) return;
                const rect = bar.getBoundingClientRect();
                const ratio = Math.min(Math.max((event.clientX - rect.left) / rect.width, 0), 1);
                audio.currentTime = ratio * audio.duration;
                updateProgress();
            });
        }

        audio.addEventListener("loadedmetadata", updateProgress);
        audio.addEventListener("timeupdate", updateProgress);
        audio.addEventListener("ended", () => {
            setPlaying(false);
            audio.currentTime = 0;
            updateProgress();
        });
    });
