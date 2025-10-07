export type FetchDataType<T> = {
  token: string,
  path: string,
  data?: T
  id?: number
}
