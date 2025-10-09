import { Card, CardContent, CardHeader } from "@/shared/components/ui/card";
import type { LucideIcon } from "lucide-react";
import type { FC } from "react";

type OutpatientCenterCardsProps = {
  data: Array<{
    title: string;
    data: number | string | null;
    icon?: LucideIcon;
  }>;
};

export const OutpatientCenterCards: FC<OutpatientCenterCardsProps> = ({
  data,
}) => {
  return (
    <>
      {data &&
        data.map(({ title, data, icon: Icon }) => (
          <Card className="flex-1">
            <CardContent className="flex gap-6 justify-start items-center">
              {Icon && (
                <Icon className="bg-black rounded-full p-2 text-white h-10 w-10" />
              )}
              <div>
                <h2 className="font-bold text-lg">{title}</h2>
                <h3 className="font-bold text-2xl">{data}</h3>
              </div>
            </CardContent>
          </Card>
        ))}
    </>
  );
};
