function data() {
    function getThemeFromLocalStorage() {
        // if user already changed the theme, use it
        if (window.localStorage.getItem('dark')) {
            return JSON.parse(window.localStorage.getItem('dark'))
        }

        // else return their preferences
        return (
            !!window.matchMedia &&
            window.matchMedia('(prefers-color-scheme: dark)').matches
        )
    }

    function setThemeToLocalStorage(value) {
        window.localStorage.setItem('dark', value)
    }

    return {
        dark: getThemeFromLocalStorage(),
        toggleTheme() {
            this.dark = !this.dark
            setThemeToLocalStorage(this.dark)
            console.log('Theme toggled. New state (dark):', this.dark);
            if (this.dark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        isSideMenuOpen: false,
        toggleSideMenu() {
            this.isSideMenuOpen = !this.isSideMenuOpen
        },
        closeSideMenu() {
            this.isSideMenuOpen = false
        },
        isNotificationsMenuOpen: false,
        toggleNotificationsMenu() {
            this.isNotificationsMenuOpen = !this.isNotificationsMenuOpen
        },
        closeNotificationsMenu() {
            this.isNotificationsMenuOpen = false
        },
        isProfileMenuOpen: false,
        toggleProfileMenu() {
            this.isProfileMenuOpen = !this.isProfileMenuOpen
        },
        closeProfileMenu() {
            this.isProfileMenuOpen = false
        },
        isPagesMenuOpen: false,
        togglePagesMenu() {
            this.isPagesMenuOpen = !this.isPagesMenuOpen
        },
        // Modal
        isModalOpen: false,
        trapCleanup: null,
        openModal() {
            this.isModalOpen = true
            this.trapCleanup = focusTrap(document.querySelector('#modal'))
        },
        closeModal() {
            this.isModalOpen = false
            this.trapCleanup()
        },
        // Global Search
        isSearchOpen: false,
        searchQuery: '',
        searchResults: [],
        searchSelectedIndex: -1,
        isSearching: false,
        openSearch() {
            this.isSearchOpen = true
            this.searchQuery = ''
            this.searchResults = []
            this.searchSelectedIndex = -1
            setTimeout(() => {
                const input = document.querySelector('#global-search-input');
                if (input) input.focus();
            }, 50);
        },
        closeSearch() {
            this.isSearchOpen = false
        },
        async performSearch() {
            if (this.searchQuery.length < 2) {
                this.searchResults = []
                return
            }
            this.isSearching = true
            try {
                const searchUrl = window.SEARCH_URL || '/api/search';
                const response = await fetch(`${searchUrl}?q=${encodeURIComponent(this.searchQuery)}`)
                this.searchResults = await response.json()
                this.searchSelectedIndex = this.searchResults.length > 0 ? 0 : -1
            } catch (error) {
                console.error('Search failed:', error)
            } finally {
                this.isSearching = false
            }
        },
        selectNextSearchResult() {
            if (this.searchResults.length === 0) return
            this.searchSelectedIndex = (this.searchSelectedIndex + 1) % this.searchResults.length
            this.scrollActiveSearchItemIntoView()
        },
        selectPrevSearchResult() {
            if (this.searchResults.length === 0) return
            this.searchSelectedIndex = (this.searchSelectedIndex - 1 + this.searchResults.length) % this.searchResults.length
            this.scrollActiveSearchItemIntoView()
        },
        scrollActiveSearchItemIntoView() {
            this.$nextTick(() => {
                const activeItem = document.querySelector('#search-result-' + this.searchSelectedIndex)
                if (activeItem) {
                    activeItem.scrollIntoView({ block: 'nearest', behavior: 'smooth' })
                }
            })
        },
        navigateToSelectedResult() {
            if (this.searchSelectedIndex >= 0 && this.searchResults[this.searchSelectedIndex]) {
                const url = this.searchResults[this.searchSelectedIndex].url;
                console.log('Navigating to:', url);
                window.location.href = url
            }
        },
    }
}
window.data = data
