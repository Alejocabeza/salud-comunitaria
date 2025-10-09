"use client";

import React, {
  useReducer,
  useMemo,
  useRef,
  type FormEventHandler,
  type ReactNode,
} from "react";
import { useVirtualizer } from "@tanstack/react-virtual";
import { Plus, MoreVertical, Edit, Trash2, Eye } from "lucide-react";
import { Button } from "./ui/button";
import { Input } from "./ui/input";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "./ui/table";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "./ui/dropdown-menu";
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetHeader,
  SheetTitle,
} from "./ui/sheet";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "./ui/alert-dialog";

export type TableDataColumn<T> = {
  header: string;
  accessor: (item: T) => React.ReactNode;
  width?: number | string;
  align?: "center" | "right" | "left";
};

export type TableDataConfig<T> = {
  title: string;
  data: T[];
  columns: TableDataColumn<T>[];
  idAccessor?: (item: T) => string;
  createComponent: React.ComponentType<{
    onSubmit: (data: Partial<T>) => void;
  }>;
  editComponent: React.ComponentType<{
    initialData?: T;
    onSubmit: (data: Partial<T>) => void;
  }>;
  viewComponent: React.ComponentType<{
    initialData?: T;
  }>;
  onCreate?: (data: Partial<T>) => Promise<void>;
  onEdit?: (id: number, data: Partial<T>) => Promise<void>;
  onDelete?: (id: number) => Promise<void>;
  searchAccessor?: (item: T) => string[];
  infoCards?: ReactNode;
};

type State<T> = {
  searchQuery: string;
  sheet: { isOpen: boolean; mode: "create" | "edit" | "view" };
  deleteDialog: boolean;
  selectedItem: T | null;
};

type Action<T> =
  | { type: "SET_SEARCH"; payload: string }
  | { type: "OPEN_SHEET"; mode: "create" | "edit" | "view"; item?: T | null }
  | { type: "CLOSE_SHEET" }
  | { type: "OPEN_DELETE_DIALOG"; item: T }
  | { type: "CLOSE_DELETE_DIALOG" };

export const TableData = <T,>({
  title,
  data,
  columns,
  idAccessor,
  createComponent: CreateComponent,
  editComponent: EditComponent,
  viewComponent: ViewComponent,
  onCreate,
  onEdit,
  onDelete,
  searchAccessor,
  infoCards,
}: TableDataConfig<T>) => {
  const [state, dispatch] = useReducer(
    (s: State<T>, a: Action<T>): State<T> => {
      switch (a.type) {
        case "SET_SEARCH":
          return { ...s, searchQuery: a.payload };
        case "OPEN_SHEET":
          return {
            ...s,
            sheet: { isOpen: true, mode: a.mode },
            selectedItem: a.item || null,
          };
        case "CLOSE_SHEET":
          return {
            ...s,
            sheet: { ...s.sheet, isOpen: false },
            selectedItem: null,
          };
        case "OPEN_DELETE_DIALOG":
          return { ...s, deleteDialog: true, selectedItem: a.item };
        case "CLOSE_DELETE_DIALOG":
          return { ...s, deleteDialog: false, selectedItem: null };
        default:
          return s;
      }
    },
    {
      searchQuery: "",
      sheet: { isOpen: false, mode: "create" },
      deleteDialog: false,
      selectedItem: null,
    }
  );

  const filteredData = useMemo(() => {
    if (!state.searchQuery) return data;
    return data.filter((item) =>
      searchAccessor
        ? searchAccessor(item).some((field) =>
            field.toLowerCase().includes(state.searchQuery.toLowerCase())
          )
        : true
    );
  }, [data, state.searchQuery, searchAccessor]);

  const parentRef = useRef<HTMLDivElement>(null);
  const rowVirtualizer = useVirtualizer({
    count: filteredData.length,
    getScrollElement: () => parentRef.current,
    estimateSize: () => 60,
    overscan: 10,
  });

  const columnWidths = useMemo(() => {
    const hasManualWidths = columns.some((c) => c.width);

    if (hasManualWidths) {
      const totalDefined = columns
        .filter((c) => c.width)
        .reduce(
          (sum, c) =>
            sum +
            (typeof c.width === "number"
              ? c.width
              : parseFloat(String(c.width)) || 0),
          0
        );

      const remainingCols = columns.filter((c) => !c.width).length;
      const remainingWidth = totalDefined < 100 ? 100 - totalDefined : 0;
      const autoWidth =
        remainingCols > 0 ? `${remainingWidth / remainingCols}%` : "auto";

      return [...columns.map((c) => c.width ?? autoWidth), "10%"];
    }

    const totalColumns = columns.length + 1;
    const columnPercentage = 100 / totalColumns;

    return [
      ...columns.map(() => `${columnPercentage}%`),
      `${columnPercentage}%`,
    ];
  }, [columns]);

  const handleCreate = async (data: Partial<T>) => {
    await onCreate?.(data);
    dispatch({ type: "CLOSE_SHEET" });
  };

  const handleEdit = async (data: Partial<T>) => {
    if (!state.selectedItem || !idAccessor) return;
    const id = parseInt(idAccessor(state.selectedItem), 10);
    if (isNaN(id)) return;
    await onEdit?.(id, data);
    dispatch({ type: "CLOSE_SHEET" });
  };

  const handleDelete = async () => {
    if (!state.selectedItem || !idAccessor) return;
    const id = parseInt(idAccessor(state.selectedItem), 10);
    if (isNaN(id)) return;
    await onDelete?.(id);
    dispatch({ type: "CLOSE_DELETE_DIALOG" });
  };

  return (
    <div className="space-y-4 p-4">
      <div className="flex flex-col md:flex-row md:justify-between md:items-center gap-4 animate-zoom-in animate-delay-0 animate-duration-slow">
        {infoCards}
      </div>

      <div className="flex flex-col md:flex-row md:justify-between md:items-center gap-4 animate-zoom-in animate-delay-150 animate-duration-slow ">
        <Input
          placeholder="Buscar..."
          value={state.searchQuery}
          onChange={(e) =>
            dispatch({ type: "SET_SEARCH", payload: e.target.value })
          }
          className="w-[20%]"
        />
        <Button
          onClick={() => dispatch({ type: "OPEN_SHEET", mode: "create" })}
          className="gap-2"
        >
          <Plus className="h-4 w-4" /> Crear Nuevo
        </Button>
      </div>

      <div
        ref={parentRef}
        className="h-max overflow-auto border rounded-lg relative animate-zoom-in animate-delay-300 animate-duration-slow "
      >
        <Table className="relative w-full table-fixed">
          <TableHeader className="sticky top-0 bg-background z-10">
            <TableRow>
              {columns.map((col, idx) => (
                <TableHead
                  key={idx}
                  style={{
                    width: columnWidths[idx],
                    minWidth: columnWidths[idx],
                  }}
                  className={`px-4 ${
                    col.align === "right"
                      ? "text-right"
                      : col.align === "center"
                      ? "text-center"
                      : "text-left"
                  }`}
                >
                  {col.header}
                </TableHead>
              ))}
              <TableHead
                className="text-center px-4"
                style={{
                  width: columnWidths[columnWidths.length - 1],
                  minWidth: columnWidths[columnWidths.length - 1],
                }}
              >
                Acciones
              </TableHead>
            </TableRow>
          </TableHeader>

          <TableBody
            style={{
              height: `${rowVirtualizer.getTotalSize()}px`,
              position: "relative",
            }}
          >
            {rowVirtualizer.getVirtualItems().map((virtualRow) => {
              const item = filteredData[virtualRow.index];
              return (
                <TableRow
                  key={idAccessor ? idAccessor(item) : virtualRow.index}
                  className="absolute w-full flex"
                  style={{
                    display: "table",
                    transform: `translateY(${virtualRow.start}px)`,
                  }}
                >
                  {columns.map((col, idx) => (
                    <TableCell
                      key={idx}
                      style={{
                        width: columnWidths[idx],
                        minWidth: columnWidths[idx],
                      }}
                      className={`overflow-hidden text-ellipsis whitespace-nowrap px-4 ${
                        col.align === "right"
                          ? "text-right"
                          : col.align === "center"
                          ? "text-center"
                          : "text-left"
                      }`}
                    >
                      {col.accessor(item)}
                    </TableCell>
                  ))}
                  <TableCell
                    className="text-center"
                    style={{ width: columnWidths[columnWidths.length - 1] }}
                  >
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="icon">
                          <MoreVertical className="h-4 w-4" />
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end">
                        <DropdownMenuItem
                          onClick={() =>
                            dispatch({ type: "OPEN_SHEET", mode: "view", item })
                          }
                        >
                          <Eye className="h-4 w-4 mr-2" /> Ver
                        </DropdownMenuItem>
                        <DropdownMenuItem
                          onClick={() =>
                            dispatch({ type: "OPEN_SHEET", mode: "edit", item })
                          }
                        >
                          <Edit className="h-4 w-4 mr-2" /> Editar
                        </DropdownMenuItem>
                        <DropdownMenuItem
                          onClick={() =>
                            dispatch({ type: "OPEN_DELETE_DIALOG", item })
                          }
                          className="text-destructive"
                        >
                          <Trash2 className="h-4 w-4 mr-2" /> Eliminar
                        </DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </TableCell>
                </TableRow>
              );
            })}
          </TableBody>
        </Table>
      </div>

      <Sheet
        open={state.sheet.isOpen}
        onOpenChange={(open) => !open && dispatch({ type: "CLOSE_SHEET" })}
      >
        <SheetContent
          side="right"
          className="w-full sm:max-w-2xl overflow-y-auto"
        >
          <SheetHeader>
            <SheetTitle>
              {state.sheet.mode === "create" && `Crear ${title}`}
              {state.sheet.mode === "edit" && `Editar ${title}`}
              {state.sheet.mode === "view" && `${title} Detalles`}
            </SheetTitle>
            <SheetDescription>
              {state.sheet.mode === "create" &&
                `Completa la información de ${title}`}
              {state.sheet.mode === "edit" &&
                `Actualiza la información de ${title}`}
              {state.sheet.mode === "view" &&
                `Información completa de ${title}`}
            </SheetDescription>
          </SheetHeader>

          <div className="mt-2 px-4">
            {state.sheet.mode === "view" && state.selectedItem ? (
              <ViewComponent initialData={state.selectedItem || undefined} />
            ) : state.sheet.mode === "edit" ? (
              <EditComponent
                initialData={state.selectedItem || undefined}
                onSubmit={handleEdit}
              />
            ) : (
              <CreateComponent onSubmit={handleCreate} />
            )}
          </div>
        </SheetContent>
      </Sheet>

      <AlertDialog
        open={state.deleteDialog}
        onOpenChange={(open) =>
          !open && dispatch({ type: "CLOSE_DELETE_DIALOG" })
        }
      >
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>¿Estás seguro?</AlertDialogTitle>
            <AlertDialogDescription>
              Esta acción eliminará permanentemente el elemento seleccionado.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancelar</AlertDialogCancel>
            <AlertDialogAction onClick={handleDelete}>
              Eliminar
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
};
