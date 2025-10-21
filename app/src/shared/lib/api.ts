let accessToken: string | null = null;
const API_URL = import.meta.env.PUBLIC_API_URL;

if (typeof window !== "undefined") {
  accessToken = localStorage.getItem("accessToken");
}

export const setAccessToken = (token: string | null) => {
  accessToken = token;
  if (typeof window !== "undefined") {
    if (token) {
      localStorage.setItem("accessToken", token);
    } else {
      localStorage.removeItem("accessToken");
    }
  }
};

let isRefreshing = false;
let failedQueue: Array<{
  resolve: (value: unknown) => void;
  reject: (reason?: any) => void;
}> = [];

const processQueue = (error: any, token: string | null = null) => {
  failedQueue.forEach((prom) => {
    if (error) {
      prom.reject(error);
    } else {
      prom.resolve(token);
    }
  });
  failedQueue = [];
};

export const api = async (endpoint: string, options: RequestInit = {}) => {
  const url = `${API_URL}${endpoint}`;

  const executeRequest = async (token: string | null) => {
    const headers = new Headers(options.headers);
    if (token) {
      headers.set("Authorization", `Bearer ${token}`);
    }
    const newOptions = {
      ...options,
      headers,
      credentials: "include" as const,
      Accept: "application/json",
      "Content-Type": "application/json",
    };
    return fetch(url, newOptions);
  };

  let response = await executeRequest(accessToken);

  if (response.status !== 401) {
    return response;
  }

  if (isRefreshing) {
    return new Promise((resolve, reject) => {
      failedQueue.push({ resolve, reject });
    }).then((token) => {
      return executeRequest(token as string | null);
    });
  }

  isRefreshing = true;

  try {
    const refreshResponse = await fetch("/api/auth/refresh", {
      method: "POST",
      credentials: "include",
    });

    if (!refreshResponse.ok) {
      throw new Error("Failed to refresh token");
    }

    const { access_token } = await refreshResponse.json();
    setAccessToken(access_token);

    processQueue(null, access_token);

    return executeRequest(access_token);
  } catch (error) {
    console.error("Fallo al refrescar el token, cerrando sesión.", error);
    processQueue(error, null);
    setAccessToken(null);
    window.location.href = "/api/auth/logout";
    return Promise.reject(error);
  } finally {
    isRefreshing = false;
  }
};
