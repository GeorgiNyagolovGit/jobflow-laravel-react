export function attachAuthInterceptor(api) {
    const interceptor = api.interceptors.response.use(
        (response) => response,
        (error) => {
            if (error.response?.status === 401) {
                window.dispatchEvent(new CustomEvent('auth:unauthorized'))
            }

            return Promise.reject(error)
        },
    )

    return () => api.interceptors.response.eject(interceptor)
}
